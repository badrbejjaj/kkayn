import { Configuration, ConfigurationParameters } from '@api/index';
import { OauthService } from '@shared/services/oauth.service';



const configurationFactory = (OAuthService: OauthService) => {
    const args: ConfigurationParameters = {};
    args.accessToken = (): string | null => {
        return OAuthService.getToken();
    };

    return new Configuration(args);
};

export let configurationProvider = {
    provide: Configuration,
    useFactory: configurationFactory,
    deps: [OauthService]
};
