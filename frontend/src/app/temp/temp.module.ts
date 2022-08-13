import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { TempRoutes } from './temp.routing';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { MaterialComponentsModule } from '../material-components.module';
import { SharedModule } from '@shared/shared.module';
import { MatFormFieldModule } from '@angular/material/form-field';
import { LoginComponent } from './login/login.component';
import { LoginLayoutComponent } from './_layout/login-layout.component';



@NgModule({
  declarations: [
    LoginComponent,
    LoginLayoutComponent
  ],
  imports: [
    MatFormFieldModule,
    CommonModule,
    ReactiveFormsModule,
    FormsModule,
    MaterialComponentsModule,
    RouterModule.forChild(TempRoutes),
    SharedModule
  ]
})
export class TempModule { }
