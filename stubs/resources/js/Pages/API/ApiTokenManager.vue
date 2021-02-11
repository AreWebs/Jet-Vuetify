<template>
    <div>
        <!-- Generate API Token -->
        <app-form-section @submitted="createApiToken">
            <template #title>
                Create API Token
            </template>

            <template #description>
                API tokens allow third-party services to authenticate with our application on your behalf.
            </template>

            <template #form>
                <!-- Token Name -->
                <v-text-field label="Name" outlined class="mt-1" 
                    v-model="createApiTokenForm.name" autofocus></v-text-field>
                <!-- <jet-input id="name" type="text" class="mt-1 block w-full" v-model="createApiTokenForm.name" autofocus /> -->

                <!-- Token Permissions -->
                <div class="pb-8" v-if="availablePermissions.length > 0">
                    <div>Permissions</div>

                    <v-row>
                        <v-col cols="12" md="6" class="py-0" v-for="permission in availablePermissions" :key="permission">
                            <v-checkbox hide-details :value="permission" v-model="createApiTokenForm.permissions"
                                :label="permission"></v-checkbox>
                        </v-col>
                    </v-row>

                </div>
            </template>

            <template #actions>
                <transition 
                
                    leave-active-class="transition ease-in duration-1000" 
                    leave-class="opacity-100" 
                    leave-to-class="opacity-0">
                    <div v-show="createApiTokenForm.recentlySuccessful" class="text-sm text-gray-600">
                        Created.
                    </div>
                </transition>

                <v-btn class="ml-3" color="secondary" :class="{ 'opacity-25': createApiTokenForm.processing }" :disabled="createApiTokenForm.processing" type="submit">
                    Create
                </v-btn>
            </template>
        </app-form-section>

        <div v-if="tokens.length > 0">
            <v-divider  class="my-8"/>

            <!-- Manage API Tokens -->
            <div class="mt-10 sm:mt-0">
                <app-action-section>
                    <template #title>
                        Manage API Tokens
                    </template>

                    <template #description>
                        You may delete any of your existing tokens if they are no longer needed.
                    </template>

                    <!-- API Token List -->
                    <template #content>
                        <div class="space-y-6">
                            <div class="d-flex items-center justify-space-between" v-for="token in tokens" :key="token.id">
                                <div>
                                    {{ token.name }}
                                </div>

                                <div class="d-flex items-center">
                                    <div class="text-sm text-gray-400" v-if="token.last_used_ago">
                                        Last used {{ token.last_used_ago }}
                                    </div>

                                    <v-btn text @click="manageApiTokenPermissions(token)"
                                        v-if="availablePermissions.length > 0" class="text-capitalize text-secondary">
                                        Permissions
                                    </v-btn>

                                    <v-btn color="red" text class="text-capitalize" @click="confirmApiTokenDeletion(token)">
                                        Delete
                                    </v-btn>
                                </div>
                            </div>
                        </div>
                    </template>
                </app-action-section>
            </div>
        </div>

        <!-- Token Value Modal -->
        <v-dialog
            v-model="displayingToken"
            max-width="672"
        >
            <v-card>
                <v-card-title class="headline">
                    API Token
                </v-card-title>

                <v-card-text>
                    Please copy your new API token. For your security, it won't be shown again.
                    
                    <div class="mt-4">
                        <v-text-field 
                            class="font-mono px-4"
                            readonly outlined
                            v-if="$page.props.jetstream.flash.token"
                            :value="$page.props.jetstream.flash.token"/>
                    </div>
                </v-card-text>

                <v-card-actions class="px-6 py-4">
                    <v-spacer></v-spacer>
                    <v-btn outlined @click.native="displayingToken = false">
                        Close
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        

        <!-- API Token Permissions Modal -->
        <v-dialog
            v-model="managingPermissionsFor"
            max-width="672"
        >
            <v-card>
                <v-card-title class="headline">
                    API Token Permissions
                </v-card-title>

                <v-card-text>
                   <v-row>
                        <v-col cols="12" md="6" class="py-0" v-for="permission in availablePermissions" :key="permission">
                            <v-checkbox hide-details :value="permission" v-model="updateApiTokenForm.permissions"
                                :label="permission"></v-checkbox>
                        </v-col>
                    </v-row>
                </v-card-text>

                <v-card-actions class="px-6 py-4">
                    <v-spacer></v-spacer>
                    <v-btn
                            outlined
                            @click.native="managingPermissionsFor = false"
                        >
                            Nevermind
                        </v-btn>

                        <v-btn
                            color="secondary" dark
                            @click.native="updateApiToken" :class="{ 'opacity-25': updateApiToken.processing }" :disabled="updateApiToken.processing"
                        >
                            Save
                        </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <!-- Delete Token Confirmation Modal -->
        <v-dialog
            v-model="apiTokenBeingDeleted"
            max-width="672"
        >
            <v-card>
                <v-card-title class="headline">
                    Delete API Token
                </v-card-title>

                <v-card-text>
                    Are you sure you would like to delete this API token?
                </v-card-text>

                <v-card-actions class="px-6 py-4">
                    <v-spacer></v-spacer>
                    <v-btn
                            outlined
                            @click.native="apiTokenBeingDeleted = false"
                        >
                            Nevermind
                        </v-btn>

                        <v-btn
                            color="secondary" dark
                            @click.native="deleteApiToken" :class="{ 'opacity-25': deleteApiTokenForm.processing }" :disabled="deleteApiTokenForm.processing"
                        >
                            Save
                        </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
    import AppActionSection from '@/Components/ActionSection'
    import AppFormSection from '@/Components/FormSection'

    export default {
        components: {
            AppActionSection,
            AppFormSection,
        },

        props: [
            'tokens',
            'availablePermissions',
            'defaultPermissions',
        ],

        data() {
            return {
                createApiTokenForm: this.$inertia.form({
                    name: '',
                    permissions: this.defaultPermissions,
                }),

                updateApiTokenForm: this.$inertia.form({
                    permissions: []
                }),

                deleteApiTokenForm: this.$inertia.form(),

                displayingToken: false,
                managingPermissionsFor: null,
                apiTokenBeingDeleted: null,
            }
        },

        methods: {
            createApiToken() {
                this.createApiTokenForm.post(route('api-tokens.store'), {
                    preserveScroll: true,
                    onSuccess: () => {
                        this.displayingToken = true
                        this.createApiTokenForm.reset()
                    }
                })
            },

            manageApiTokenPermissions(token) {
                this.updateApiTokenForm.permissions = token.abilities

                this.managingPermissionsFor = token
            },

            updateApiToken() {
                this.updateApiTokenForm.put(route('api-tokens.update', this.managingPermissionsFor), {
                    preserveScroll: true,
                    preserveState: true,
                    onSuccess: () => (this.managingPermissionsFor = null),
                })
            },

            confirmApiTokenDeletion(token) {
                this.apiTokenBeingDeleted = token
            },

            deleteApiToken() {
                this.deleteApiTokenForm.delete(route('api-tokens.destroy', this.apiTokenBeingDeleted), {
                    preserveScroll: true,
                    preserveState: true,
                    onSuccess: () => (this.apiTokenBeingDeleted = null),
                })
            },
        },
    }
</script>
