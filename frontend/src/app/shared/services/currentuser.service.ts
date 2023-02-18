import { Injectable } from '@angular/core';
import { OauthService } from './oauth.service';
import { combineLatest, pipe, of, Observable, ReplaySubject, Subscription } from 'rxjs';
import { map, tap } from 'rxjs/operators';
import { Router } from '@angular/router';
import { User, UserService } from '@api/index';
import { OAuthData, SignUpCredentials } from '@shared/Interfaces';
@Injectable({
    providedIn: 'root'
})
export class CurrentUserService {
    retrieving = false;
    // tslint:disable-next-line: variable-name
    private readonly _userSubject = new ReplaySubject<User>(1);
    // tslint:disable-next-line: variable-name
    private _user: User;
    // tslint:disable-next-line: variable-name
    private _userSubscription: Subscription;

    public constructor(
        private oAuthService: OauthService,
        private userService: UserService,
        private router: Router
    ) {}

    get currentUser$(): Observable<User> {
        this.retrieveUser();

        return this._userSubject.asObservable();
    }

    public redirectToLogin(): void {

        // this.router.navigate(['/example']);
    }

    public isAuthenticated(isAuth = 'nada'): Observable<boolean> {
        console.log(isAuth);
        return combineLatest([
          of(this.oAuthService.isLogged()),
          this.currentUser$.pipe(map((u: User) => u !== undefined ))
        ])
          .pipe(map((result: [boolean, boolean]) => result[0] && result[1]));
      }

    public retrieveUser(): void {
        if (!this.oAuthService.isLogged()) {
            this.redirectToLogin();
            return;
        }

        if (this._user || this.retrieving ) {
            return;
        }

        this.retrieving  = true;
        this._userSubscription = this.userService.getCurrentUser()
        .subscribe(
            (user: User) => {
                this._userSubscription.unsubscribe();
                this._userSubscription = undefined;

                this._user = user;
                this._userSubject.next(this._user);
                this.retrieving = false;
            },
            () => this.logout()
        );
    }

    public login(credentials): Observable<OAuthData> {
        return this.oAuthService.fetchAccessToken(credentials)
            .pipe(tap(() => this.retrieveUser()));
    }

    public logout(): void
    {
        this._user = null;
        this.oAuthService.removeToken();
        this.redirectToLogin();
    }

    public signUpUser(credentials: SignUpCredentials): Observable<OAuthData>
    {
        return this.oAuthService.signUpUser(credentials)
            .pipe(tap(() => this.retrieveUser));
    }

}
