import { Routes } from '@angular/router';
import { LoginComponent } from './login/login.component';
import { LoginLayoutComponent } from './_layout/login-layout.component';

export const TempRoutes: Routes = [
    {
        path: '',
        component: LoginLayoutComponent,
        children: [
            {
                path: '',
                component: LoginComponent,
                pathMatch: 'full'
            }
            // {
            //     path: 'forgotPassword',
            //     component: ForgotPwdComponent
            // },
            // {
            //     path: 'resetPassword',
            //     component: ResetPwdComponent
            // },
        ]
    }
];
