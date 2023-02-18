import { Component, ViewEncapsulation, OnInit } from '@angular/core';
import { User } from '@api/index';
import { CurrentUserService } from '@shared/services/currentuser.service';

@Component({
    selector     : 'landing-home',
    templateUrl  : './home.component.html',
    encapsulation: ViewEncapsulation.None
})
export class LandingHomeComponent implements OnInit
{
    currentUser: User;
    /**
     * Constructor
     */
    constructor(private _currentUserService: CurrentUserService)
    {

    }

    ngOnInit(): void {
        this._currentUserService.currentUser$.subscribe(
            (user: User) => {
              this.currentUser = user;
              console.log(user);
            });
    }
}
