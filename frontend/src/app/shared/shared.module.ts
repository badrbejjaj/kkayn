import { CommonModule } from '@angular/common';
import { LocalStorageService } from './services/localStorageService.service';
import { NgModule } from '@angular/core';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';

@NgModule({
    declarations: [],
    imports: [CommonModule, FormsModule, ReactiveFormsModule],
    providers: [LocalStorageService],
    exports: [CommonModule, FormsModule, ReactiveFormsModule],
})
export class SharedModule {}
