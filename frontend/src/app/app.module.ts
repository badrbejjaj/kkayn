import { HttpClientModule } from '@angular/common/http';
import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { configurationProvider } from '@shared/helpers/configuration.provider';
import { BASE_PATH } from '@api/index';
import { environment } from 'src/environments/environment';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { MaterialComponentsModule } from './material-components.module';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { SharedModule } from '@shared/shared.module';

export function getApiBasePath(): string {
  return environment.apiAccessPoint;
}

@NgModule({
  declarations: [
    AppComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    MaterialComponentsModule,
    HttpClientModule,
    BrowserAnimationsModule,
    SharedModule

  ],
  providers: [
    configurationProvider,
    {
      provide: BASE_PATH,
      useFactory: getApiBasePath
    }
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
