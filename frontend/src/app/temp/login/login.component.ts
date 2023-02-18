import { Component, EventEmitter, Output } from '@angular/core';
import { AbstractControl, UntypedFormBuilder, UntypedFormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { CurrentUserService } from '@shared/services/currentuser.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent {


  @Output() isLoading: EventEmitter<boolean> = new EventEmitter();
  public form: UntypedFormGroup;
  public loading = false;

  constructor(
    private currentUserService: CurrentUserService,
    private router: Router,
    private fb: UntypedFormBuilder
  ) {
    this.form =  this.fb.group({
      username: ['', [Validators.required]],
      password: ['', [Validators.required]]
    });

  }

  get username(): AbstractControl {
    return this.form.get('username');
  }
  get password(): AbstractControl {
    return this.form.get('password');
  }

  onloading(event): void {
    this.loading = event;
    this.isLoading.emit(event);
  }

  onSubmit(): void {
    this.onloading(true);
    if (this.form.valid) {
      this.currentUserService.login(this.form.value).subscribe( (response) => {
        this.router.navigate(['/']);
        this.onloading(true);
      }, (error) => {
        console.log(error.message);
        this.onloading(false);
      });
    }
  }


}
