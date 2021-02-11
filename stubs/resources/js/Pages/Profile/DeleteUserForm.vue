<template>
    <app-action-section>
        <template #title>
            Delete Account
        </template>

        <template #description>
            Permanently delete your account.
        </template>

        <template #content>
            <div class="text-sm">
                Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
            </div>

            <div class="mt-5">
                <v-btn color="secondary" @click.native="confirmUserDeletion">
                    Delete Account
                </v-btn>
            </div>

            <v-dialog
                v-model="confirmingUserDeletion"
                max-width="672"
            >
                <v-card>
                    <v-card-title class="headline">
                        Delete Account
                    </v-card-title>

                    <v-card-text>
                        Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.
                        
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
                            @click.native="deleteUser" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                        >
                            Delete Account
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
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
                confirmingUserDeletion: false,

                form: this.$inertia.form({
                    password: '',
                })
            }
        },

        methods: {
            confirmUserDeletion() {
                this.confirmingUserDeletion = true;

                setTimeout(() => this.$refs.password.focus(), 250)
            },

            deleteUser() {
                this.form.delete(route('current-user.destroy'), {
                    preserveScroll: true,
                    onSuccess: () => this.closeModal(),
                    onError: () => this.$refs.password.focus(),
                    onFinish: () => this.form.reset(),
                })
            },

            closeModal() {
                this.confirmingUserDeletion = false

                this.form.reset()
            },
        },
    }
</script>
