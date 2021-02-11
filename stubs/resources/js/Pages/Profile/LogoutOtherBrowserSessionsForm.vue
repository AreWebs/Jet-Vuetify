<template>
    <app-action-section>
        <template #title>
            Browser Sessions
        </template>

        <template #description>
            Manage and logout your active sessions on other browsers and devices.
        </template>

        <template #content>
            <div class="text-sm">
                If necessary, you may logout of all of your other browser sessions across all of your devices. Some of your recent sessions are listed below; however, this list may not be exhaustive. If you feel your account has been compromised, you should also update your password.
            </div>

            <!-- Other Browser Sessions -->
            <div class="mt-5 pt-6" v-if="sessions.length > 0">
                <div class="flex items-center" v-for="(session, i) in sessions" :key="i">
                    <div>
                        <v-icon v-if="session.agent.is_desktop">mdi-desktop-tower-monitor</v-icon>
                        <v-icon v-else>mdi-cellphone</v-icon>

                        <!-- <svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor" 
                            class="w-8 h-8 text-gray-500" v-if="session.agent.is_desktop">
                            <path d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>

                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" 
                        class="w-8 h-8 text-gray-500" v-else>
                            <path d="M0 0h24v24H0z" stroke="none"></path><rect x="7" y="4" width="10" height="16" rx="1"></rect><path d="M11 5h2M12 17v.01"></path>
                        </svg> -->
                    </div>

                    <div class="ml-3">
                        <div class="text-sm text-gray-600">
                            {{ session.agent.platform }} - {{ session.agent.browser }}
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">
                                {{ session.ip_address }},

                                <span class="green--text accent-4 font-semibold" v-if="session.is_current_device">This device</span>
                                <span v-else>Last active {{ session.last_active }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center mt-5">
                <v-btn color="secondary" @click.native="confirmLogout">
                    Logout Other Browser Sessions
                </v-btn>

                <transition 
                
                    leave-active-class="transition ease-in duration-1000" 
                    leave-class="opacity-100" 
                    leave-to-class="opacity-0">
                    <div v-show="form.recentlySuccessful" class="text-sm text-gray-600">
                        Saved.
                        Done.
                    </div>
                </transition>

                <v-dialog
                    v-model="confirmingLogout"
                    max-width="672"
                >
                    <v-card>
                        <v-card-title class="headline">
                            Logout Other Browser Sessions
                        </v-card-title>

                        <v-card-text>
                            Please enter your password to confirm you would like to logout of your other browser sessions across all of your devices.
                            
                            <div class="mt-4">
                                <v-text-field type="password" class="mt-1" label=Password
                                    outlined
                                    v-model="form.password"
                                    @keyup.enter.native="confirmPassword"
                                    :error-messages="form.error"></v-text-field>
                            </div>
                        </v-card-text>

                        <v-card-actions class="px-6 py-4">
                            <v-spacer></v-spacer>
                            <v-btn
                                outlined
                                @click.native="closeModal"
                            >
                                Nevermind
                            </v-btn>

                            <v-btn
                                color="secondary"
                                @click.native="logoutOtherBrowserSessions" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                            >
                                Logout Other Browser Sessions
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </div>
        </template>
    </app-action-section>
</template>

<script>
    import AppActionSection from '@/Components/ActionSection'
    import AppConfirmsPassword from '@/Components/ConfirmsPassword'


    export default {
        components: {
            AppActionSection,
            AppConfirmsPassword,
        },
        
        props: ['sessions'],

        data() {
            return {
                confirmingLogout: false,

                form: this.$inertia.form({
                    password: '',
                })
            }
        },

        methods: {
            confirmLogout() {
                this.confirmingLogout = true

                setTimeout(() => this.$refs.password.focus(), 250)
            },

            logoutOtherBrowserSessions() {
                this.form.delete(route('other-browser-sessions.destroy'), {
                    preserveScroll: true,
                    onSuccess: () => this.closeModal(),
                    onError: () => this.$refs.password.focus(),
                    onFinish: () => this.form.reset(),
                })
            },

            closeModal() {
                this.confirmingLogout = false

                this.form.reset()
            },
        },
    }
</script>
