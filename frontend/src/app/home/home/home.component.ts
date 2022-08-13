import { Component, OnInit } from '@angular/core';
import { User } from '@api/index';
import { CurrentUserService } from '@shared/services/currentuser.service';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.scss']
})
export class HomeComponent implements OnInit {
  currentUser: User;
  constructor(
    public currentUserService: CurrentUserService
  ) { }

  ngOnInit(): void {
  this.currentUserService.currentUser$.subscribe(
      (user: User) => {
        this.currentUser = user;
        console.log(user);
      });
  }

}
