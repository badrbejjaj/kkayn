import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { BrowserModule } from '@angular/platform-browser';
import { HttpClientModule } from '@angular/common/http';
import { NgModule } from '@angular/core';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { MaterialComponentsModule } from './material-components.module';
import { environment } from 'environments/environment';

import { configurationProvider } from '@shared/helpers/configuration.provider';
import { BASE_PATH } from '@api/variables';
import { LayoutModule } from '@layout/layout.module';
import { FuseModule } from '@fuse';
import { FuseConfigModule } from '@fuse/services/config';
import { appConfig } from '@core/config/app.config';
import { CoreModule } from '@core/core.module';
import { MarkdownModule } from 'ngx-markdown';
import { FuseMockApiModule } from '@fuse/lib/mock-api';
import { mockApiServices } from './mock-api';
export const getApiBasePath = (): string => environment.apiAccessPoint;

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

    // Fuse, FuseConfig & FuseMockAPI
    FuseModule,
    FuseConfigModule.forRoot(appConfig),
    FuseMockApiModule.forRoot(mockApiServices),
    // Core module of your application
    CoreModule,
    // Layout module of your application
    LayoutModule,
    // 3rd party modules that require global configuration via forRoot
    MarkdownModule.forRoot({})
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
