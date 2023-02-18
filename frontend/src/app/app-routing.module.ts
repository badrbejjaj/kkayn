import { NgModule } from '@angular/core';
import {
    ExtraOptions,
    PreloadAllModules,
    RouterModule,
    Routes,
} from '@angular/router';
import { AuthGuard } from '@core/auth/guards/auth.guard';
import { NoAuthGuard } from '@core/auth/guards/noAuth.guard';
import { LayoutComponent } from '@layout/layout.component';
import { InitialDataResolver } from './app.resolvers';

const routerConfig: ExtraOptions = {
    preloadingStrategy: PreloadAllModules,
    scrollPositionRestoration: 'enabled',
};

const routes: Routes = [
    // Redirect empty path to '/signed-in-redirect'
    { path: '', pathMatch: 'full', redirectTo: 'dashboards/project' },

    // Redirect signed in user to the '/signed-in-redirect'
    //
    // After the user signs in, the sign in page will redirect the user to the 'signed-in-redirect'
    // path. Below is another redirection for that path to redirect the user to the desired
    // location. This is a small convenience to keep all main routes together here on this file.
    { path: 'signed-in-redirect', pathMatch: 'full', redirectTo: 'dashboards/project' },

    // Auth routes for guests
    {
        path: '',
        component: LayoutComponent,
        canActivate: [NoAuthGuard],
        canActivateChild: [NoAuthGuard],
        data: {
            layout: 'empty',
        },
        children: [
            {
                path: 'confirmation-required',
                loadChildren: () =>
                    import(
                        'app/modules/auth/confirmation-required/confirmation-required.module'
                    ).then(m => m.AuthConfirmationRequiredModule),
            },
            {
                path: 'forgot-password',
                loadChildren: () =>
                    import(
                        'app/modules/auth/forgot-password/forgot-password.module'
                    ).then(m => m.AuthForgotPasswordModule),
            },
            {
                path: 'reset-password',
                loadChildren: () =>
                    import(
                        'app/modules/auth/reset-password/reset-password.module'
                    ).then(m => m.AuthResetPasswordModule),
            },
            {
                path: 'sign-in',
                loadChildren: () =>
                    import('app/modules/auth/sign-in/sign-in.module').then(
                        m => m.AuthSignInModule
                    ),
            },
            {
                path: 'sign-up',
                loadChildren: () =>
                    import('app/modules/auth/sign-up/sign-up.module').then(
                        m => m.AuthSignUpModule
                    ),
            },
        ],
    },

    // Auth routes for authenticated users
    {
        path: '',
        component: LayoutComponent,
        canActivate: [NoAuthGuard],
        canActivateChild: [NoAuthGuard],
        data: {
            layout: 'empty',
        },
        children: [
            {
                path: 'sign-out',
                loadChildren: () =>
                    import('app/modules/auth/sign-out/sign-out.module').then(
                        m => m.AuthSignOutModule
                    ),
            },
            {
                path: 'unlock-session',
                loadChildren: () =>
                    import(
                        'app/modules/auth/unlock-session/unlock-session.module'
                    ).then(m => m.AuthUnlockSessionModule),
            },
        ],
    },

    // Landing routes
    {
        path: '',
        component: LayoutComponent,
        data: {
            layout: 'empty',
        },
        children: [
            {
                path: 'home',
                loadChildren: () =>
                    import('app/modules/landing/home/home.module').then(
                        m => m.LandingHomeModule
                    ),
            },
        ],
    },

    // Admin routes
    {
        path: '',
        canActivate: [AuthGuard],
        canActivateChild: [AuthGuard],
        component: LayoutComponent,
        resolve: {
            initialData: InitialDataResolver,
        },
        children: [
            // Dashboards
            {path: 'dashboards', children: [
                {path: 'project', loadChildren: () => import('app/modules/admin/dashboards/project/project.module').then(m => m.ProjectModule)},
                {path: 'analytics', loadChildren: () => import('app/modules/admin/dashboards/analytics/analytics.module').then(m => m.AnalyticsModule)},
            ]},
            {
                path: 'example',
                loadChildren: () =>
                    import('app/modules/admin/example/example.module').then(
                        m => m.ExampleModule
                    ),
            },
        ],
    },
];

@NgModule({
    imports: [RouterModule.forRoot(routes, routerConfig)],
    exports: [RouterModule],
})
export class AppRoutingModule {}
