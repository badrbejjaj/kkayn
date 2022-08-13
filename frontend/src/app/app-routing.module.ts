import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { OAuthGaurd } from '@shared/services/oauth.guard';

const routes: Routes = [
  {
    path  : 'login' ,
    loadChildren: () => import('./temp/temp.module').then(m => m.TempModule)
  },
  {
    path  : '' ,
    loadChildren: () => import('./home/home.module').then(m => m.HomeModule),
    canActivate: [OAuthGaurd]
  }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
