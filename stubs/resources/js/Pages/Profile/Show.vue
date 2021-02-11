<template>
  <app-layout>
    <template #header>
      <h2>
        Profile
      </h2>
    </template>

    <div>
      <div class="mx-auto py-10 px-sm-6 px-lg-8">
        <div v-if="$page.props.jetstream.canUpdateProfileInformation">
            <update-profile-information-form :user="$page.props.user" />
          
            <v-divider class="my-8" />
        </div>

        <div v-if="$page.props.jetstream.canUpdatePassword">
            <update-password-form class="mt-10 mt-sm-0" />

            <v-divider class="my-8" />
        </div>

        <div v-if="$page.props.jetstream.canManageTwoFactorAuthentication">
            <two-factor-authentication-form class="mt-10 mt-sm-0" />

            <v-divider class="my-8" />
        </div>

        

        <logout-other-browser-sessions-form :sessions="sessions" class="mt-10 mt-sm-0" />
  
        <template v-if="$page.props.jetstream.hasAccountDeletionFeatures">
          <v-divider class="my-8" />

          <delete-user-form class="mt-10 mt-sm-0" />
        </template>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout';
import UpdateProfileInformationForm from './UpdateProfileInformationForm';
import UpdatePasswordForm from './UpdatePasswordForm';
import TwoFactorAuthenticationForm from './TwoFactorAuthenticationForm';
import LogoutOtherBrowserSessionsForm from './LogoutOtherBrowserSessionsForm';
import DeleteUserForm from './DeleteUserForm';

export default {
  components: {
    AppLayout,
	  UpdateProfileInformationForm,
    UpdatePasswordForm,
    TwoFactorAuthenticationForm,
    LogoutOtherBrowserSessionsForm,
    DeleteUserForm,
  },

  props: ['sessions'],
}
</script>