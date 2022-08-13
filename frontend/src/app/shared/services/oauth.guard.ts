import { CanActivate, Router, RouterStateSnapshot, ActivatedRouteSnapshot } from '@angular/router';
import { OauthService } from './oauth.service';
import { CurrentUserService } from './currentuser.service';
import { Observable } from 'rxjs';
import { tap } from 'rxjs/operators';
import { Injectable } from '@angular/core';

@Injectable({
    providedIn: 'root'
})
export class OAuthGaurd implements CanActivate {
    constructor(
        private router: Router,
        private oauthService: OauthService,
        private currentUserService: CurrentUserService
    ) {}

    canActivate( route: ActivatedRouteSnapshot, state: RouterStateSnapshot ): Observable<boolean> {
        return this.currentUserService.isAuthenticated()
            .pipe(tap((logged) => {
                if (!logged) {
                    this.router.navigate(['/login']);
                    return false;
                }

                return true;
            }));
    }
}
