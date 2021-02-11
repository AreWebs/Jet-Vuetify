<template>
    <app-form-section @submitted="updatePassword">
        <template #title>
            Update Password
        </template>

        <template #description>
            Ensure your account is using a long, random password to stay secure.
        </template>

        <template #form>

            <v-text-field
                label="Current Password"
                outlined autocomplete="current-password"
                 v-model="form.current_password"
                :error-messages="form.errors.current_password"
                :append-icon="showCP ? 'mdi-eye' : 'mdi-eye-off'"
                :type="showCP ? 'text' : 'password'"
                @click:append="showCP = !showCP"></v-text-field>

            <v-text-field outlined label="Password" required
                autocomplete="new-password"
                v-model="form.password"
                :error-messages="form.errors.password"
                :append-icon="showP ? 'mdi-eye' : 'mdi-eye-off'"
                :type="showP ? 'text' : 'password'"
                hint="At least 8 characters"
                @click:append="showP = !showP"></v-text-field>

            <v-text-field outlined label="Confirm Password" required
                autocomplete="new-password"
                v-model="form.password_confirmation"
                :error-messages="form.errors.password_confirmation"
                :append-icon="showNP ? 'mdi-eye' : 'mdi-eye-off'"
                :type="showNP ? 'text' : 'password'"
                @click:append="showNP = !showNP"></v-text-field>

        </template>

        <template #actions>
            <transition 
                
                leave-active-class="transition ease-in duration-1000" 
                leave-class="opacity-100" 
                leave-to-class="opacity-0">
                <div v-show="form.recentlySuccessful" class="text-sm text-gray-600">
                    Saved.
                </div>
            </transition>

            <v-btn class="ml-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" type="submit" color="secondary">
                Save
            </v-btn>
        </template>
    </app-form-section>
</template>

<script>
    import AppFormSection from '@/Components/FormSection'

    export default {
        components: {
            AppFormSection,
        },

        data() {
            return {
                showCP: false,
                showP: false,
                showNP: false,
                form: this.$inertia.form({
                    current_password: '',
                    password: '',
                    password_confirmation: '',
                }),
            }
        },

        methods: {
            updatePassword() {
                this.form.put(route('user-password.update'), {
                    errorBag: 'updatePassword',
                    preserveScroll: true,
                    onSuccess: () => this.form.reset(),
                    onError: () => {
                        if (this.form.errors.password) {
                            this.form.reset('password', 'password_confirmation')
                            this.$refs.password.focus()
                        }

                        if (this.form.errors.current_password) {
                            this.form.reset('current_password')
                            this.$refs.current_password.focus()
                        }
                    }
                })
            },
        },
    }
</script>
