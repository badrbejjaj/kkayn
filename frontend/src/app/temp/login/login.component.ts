import { Component, EventEmitter, Output } from '@angular/core';
import { AbstractControl, FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { CurrentUserService } from '@shared/services/currentuser.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent {
  @Output() isLoading: EventEmitter<boolean> = new EventEmitter();
  public form: FormGroup;
  public loading = false;

  constructor(
    private currentUserService: CurrentUserService,
    private router: Router,
    private fb: FormBuilder
  ) {
    this.form =  this.fb.group({
      username: ['', [Validators.required]],
      password: ['', [Validators.required]]
    });

  }

  onSubmit(): void {
    this.Onloading(true);
    if (this.form.valid) {
      this.currentUserService.login(this.form.value).subscribe( (response) => {
        this.router.navigate(['/']);
        this.Onloading(true);
      }, (error) => {
        console.log(error.message);
        this.Onloading(false);
      });
    }
  }

  get username(): AbstractControl {
    return this.form.get('username');
  }
  get password(): AbstractControl {
    return this.form.get('password');
  }
  Onloading(event): void {
    this.loading = event;
    this.isLoading.emit(event);
  }
}
