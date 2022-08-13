import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { LocalStorageService } from './services/localStorageService.service';



@NgModule({
  declarations: [

  ],
  imports: [
    CommonModule
  ],
  providers: [
    LocalStorageService
  ]
})
export class SharedModule { }
