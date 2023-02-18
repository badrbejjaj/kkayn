import { Injectable, Injector } from '@angular/core';
import { Router } from '@angular/router';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';
import { tap } from 'rxjs/operators';
import { LocalStorageService } from './localStorageService.service';
import { environment } from 'environments/environment';
import jwtDecode from 'jwt-decode';
import { ApiTokenResponse, UserService } from '@api/index';
import { OAuthData, SignUpCredentials } from '@shared/Interfaces';



@Injectable({
  providedIn: 'root'
})
export class OauthService {

  protected url = null;
  protected lsTokenKey = 'oauthtoken';
  protected readonly formDataHeaders = new HttpHeaders({
    'Content-Type': 'application/json'
  });

  constructor(
    protected http: HttpClient,
    public lsService: LocalStorageService,
    private readonly router: Router,
    private injector: Injector
  ) {
    this.url = environment.apiAccessPoint;
  }

  public fetchAccessToken(credentials: any): Observable<OAuthData> {
    return this.http
    .post(this.url + '/login', credentials, { headers: this.formDataHeaders})
    .pipe(tap((token: OAuthData) => this.setToken(token)));
  }

  public setToken(token): void {
    token = JSON.stringify(token);
    this.lsService.set(this.lsTokenKey, token);
  }

  public isLogged(): boolean {
    return this.getOAuthData() && !this.isTokenExpired();
  }

  protected getOAuthData(): OAuthData | null {
    const token: OAuthData = this.lsService.get(this.lsTokenKey);
    return token || null;
  }

  public getToken(): string | null {
    if (this.isTokenExpired(0)) {
      this.removeToken();
      return null;
    }

    if (this.isTokenExpired(60 * 20)) {
      this.refreshToken();
    }

    const oauthData: OAuthData = this.getOAuthData();
    return oauthData ? JSON.parse(oauthData as any).token : null;
  }

  private refreshToken(): Observable<ApiTokenResponse>  {
    const userService: UserService = this.injector.get(UserService);

    return userService.refreshUserToken()
    .pipe(tap((res: any) =>  this.setToken(res.token)));
  }

  private isTokenExpired(delay: number = 0): boolean {
    const oauthData: any = this.getOAuthData();
    if (oauthData === undefined || oauthData === null) {
      return true;
    }
    const token = JSON.parse(oauthData).token;
    const payload: any = jwtDecode(token);
    return (payload.exp - delay) < (Date.now() / 1000);
  }

  public removeToken(): void {
    this.lsService.remove(this.lsTokenKey);
    this.router.navigate(['/login']);
  }

  public signUpUser(credentials: SignUpCredentials): Observable<OAuthData> {
    return this.http
      .post(this.url + '/signup', credentials, { headers: this.formDataHeaders})
      .pipe(tap((token: OAuthData) => this.setToken(token)));
  }
}
