<template>
    <div>
        <div v-if="userPermissions.canAddTeamMembers">
            <v-divider class="my-8" />

            <!-- Add Team Member -->
            <app-form-section @submitted="addTeamMember">
                <template #title>
                    Add Team Member
                </template>

                <template #description>
                    Add a new team member to your team, allowing them to collaborate with you.
                </template>

                <template #form>
                    <div class="mb-3">
                        <div class="text-sm text-gray-600">
                            Please provide the email address of the person you would like to add to this team.
                        </div>
                    </div>

                    <!-- Member Email -->
                    <v-text-field label="Email" dense hide-details
                        outlined type="email" v-model="addTeamMemberForm.email"></v-text-field>
                    <!-- <jet-input-error :message="addTeamMemberForm.errors.email" class="mt-2" /> -->

                    <!-- Role -->
                    <div v-if="availableRoles.length > 0" class="my-4">
                        <div class="mt-2">Role</div>
                        <!-- <jet-input-error :message="addTeamMemberForm.errors.role" class="mt-2" /> -->


                        <div class="mb-4 relative z-0 mt-1 border border-gray-200 rounded-lg cursor-pointer">
                            <button type="button" class="team-btn focus:shadow-outline-blue"
                                :class="{'border-t border-gray-200 rounded-t-none': i > 0, 'rounded-b-none': i != Object.keys(availableRoles).length - 1}"
                                @click="addTeamMemberForm.role = role.key"
                                v-for="(role, i) in availableRoles"
                                :key="role.key">
                                <div :class="{'opacity-50': addTeamMemberForm.role && addTeamMemberForm.role != role.key}">
                                    <div class="d-flex items-center">
                                        <div class="text-sm text-gray-600" :class="{'font-semibold': addTeamMemberForm.role == role.key}">
                                            {{ role.name }}
                                        </div>
                                        <v-icon v-if="addTeamMemberForm.role == role.key" style="color: rgb(52, 211, 153);" class="ml-2">mdi-check-circle-outline</v-icon>
                                    </div>

                                    <div class="mt-2 text-xs text-gray-600">
                                        {{ role.description }}
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>
                </template>

                <template #actions>
                    <transition 
                        leave-active-class="transition ease-in duration-1000" 
                        leave-class="opacity-100" 
                        leave-to-class="opacity-0">
                        <div v-show="addTeamMemberForm.recentlySuccessful" class="text-sm text-gray-600">
                            Added.
                        </div>
                    </transition>

                    <v-btn color="secondary" class="ml-4" :class="{ 'opacity-25': addTeamMemberForm.processing }" :disabled="addTeamMemberForm.processing" type="submit">
                        Add
                    </v-btn>
                </template>
            </app-form-section>
        </div>

        <div v-if="team.team_invitations.length > 0 && userPermissions.canAddTeamMembers">
            <v-divider class="my-8" />

            <!-- Team Member Invitations -->
            <app-action-section class="mt-10 sm:mt-0">
                <template #title>
                    Pending Team Invitations
                </template>

                <template #description>
                    These people have been invited to your team and have been sent an invitation email. They may join the team by accepting the email invitation.
                </template>

                <!-- Pending Team Member Invitation List -->
                <template #content>
                    <div class="pb-4">
                        <div class="d-flex items-center justify-space-between" v-for="invitation in team.team_invitations" :key="invitation.id">
                            <div class="text-gray-600">{{ invitation.email }}</div>

                            
                            <!-- Cancel Team Invitation -->
                            <v-btn color="red" dark
                                @click="cancelTeamInvitation(invitation)"
                                v-if="userPermissions.canRemoveTeamMembers">
                                Cancel
                            </v-btn>
                            
                        </div>
                    </div>
                </template>
            </app-action-section>
        </div>

        <div v-if="team.users.length > 0">
            <v-divider class="my-8" />

            <!-- Manage Team Members -->
            <app-action-section class="mt-10 sm:mt-0">
                <template #title>
                    Team Members
                </template>

                <template #description>
                    All of the people that are part of this team.
                </template>

                <!-- Team Member List -->
                <template #content>
                    <div class="pb-4">
                        <div class="d-flex items-center justify-space-between" v-for="user in team.users" :key="user.id">
                            <div class="flex items-center">
                                <v-avatar>
                                    <img :src="user.profile_photo_url" :alt="user.name">
                                </v-avatar>
                                <div class="ml-4">{{ user.name }}</div>
                            </div>

                            <div class="d-flex items-center">
                                <!-- Manage Team Member Role -->
                                <v-btn text class="ml-2 text-sm text--secondary text-capitalize"
                                        @click="manageRole(user)"
                                        v-if="userPermissions.canAddTeamMembers && availableRoles.length">
                                    {{ displayableRole(user.membership.role) }}
                                </v-btn>

                                <div class="ml-2 text-sm text-gray-400" v-else-if="availableRoles.length">
                                    {{ displayableRole(user.membership.role) }}
                                </div>

                                <!-- Leave Team -->
                                <v-btn color="red" text
                                    class="ml-3 text-capitalize"
                                    @click="confirmLeavingTeam"
                                    v-if="$page.props.user.id === user.id">
                                    Leave
                                </v-btn>

                                <!-- Remove Team Member -->
                                <v-btn color="red" text
                                    class="ml-3 text-capitalize"
                                    @click="confirmTeamMemberRemoval(user)"
                                    v-if="userPermissions.canRemoveTeamMembers">
                                    Remove
                                </v-btn>
                            </div>
                        </div>
                    </div>
                </template>
            </app-action-section>
        </div>

        <!-- Role Management Modal -->
        <v-dialog
            v-model="currentlyManagingRole"
            max-width="672"
        >
            <v-card>
                <v-card-title class="headline">
                    Manage Role
                </v-card-title>
                
                <v-card-text>
                    <div v-if="managingRoleFor">
                        <div class="relative z-0 mt-1 border border-gray-200 rounded-lg cursor-pointer">
                            <button type="button" class="team-btn"
                                :class="{'border-t border-gray-200 rounded-t-none': i > 0, 'rounded-b-none': i != Object.keys(availableRoles).length - 1}"
                                @click="updateRoleForm.role = role.key"
                                v-for="(role, i) in availableRoles"
                                :key="role.key">
                                <div :class="{'opacity-50': updateRoleForm.role && updateRoleForm.role != role.key}">
                                    <div class="d-flex items-center">
                                        <div class="text-sm text-gray-600" :class="{'font-semibold': updateRoleForm.role == role.key}">
                                            {{ role.name }}
                                        </div>
                                        <v-icon v-if="updateRoleForm.role == role.key" style="color: rgb(52, 211, 153);" class="ml-2">mdi-check-circle-outline</v-icon>
                                    </div>

                                    <div class="mt-2 text-xs text-gray-600">
                                        {{ role.description }}
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>
                </v-card-text>

                <v-card-actions class="px-6 py-4">
                    <v-spacer></v-spacer>
                    <v-btn
                        outlined
                        @click.native="currentlyManagingRole = false"
                    >
                        Nevermind
                    </v-btn>

                    <v-btn
                        color="secondary"
                        @click.native="updateRole" :class="{ 'opacity-25': updateRoleForm.processing }" :disabled="updateRoleForm.processing"
                    >
                        Save
                    </v-btn>
                </v-card-actions>

            </v-card>
        </v-dialog>

        <!-- Leave Team Confirmation Modal -->
        <v-dialog
            v-model="confirmingLeavingTeam"
            max-width="672"
        >
            <v-card>
                <v-card-title class="headline">
                    Leave Team
                </v-card-title>

                <v-card-text>
                    Are you sure you would like to leave this team?
                </v-card-text>

                <v-card-actions class="px-6 py-4">
                    <v-spacer></v-spacer>
                    <v-btn
                        outlined
                        @click.native="confirmingLeavingTeam = false"
                    >
                        Nevermind
                    </v-btn>

                    <v-btn
                        color="secondary"
                        @click.native="leaveTeam" :class="{ 'opacity-25': leaveTeamForm.processing }" :disabled="leaveTeamForm.processing"
                    >
                        Leave
                    </v-btn>
                </v-card-actions>

            </v-card>
        </v-dialog>

        <!-- Remove Team Member Confirmation Modal -->
        <v-dialog
            v-model="teamMemberBeingRemoved"
            max-width="672"
        >
            <v-card>
                <v-card-title class="headline">
                    Remove Team Member
                </v-card-title>

                <v-card-text>
                    Are you sure you would like to remove this person from the team?
                </v-card-text>

                <v-card-actions class="px-6 py-4">
                    <v-spacer></v-spacer>
                    <v-btn
                        outlined
                        @click.native="teamMemberBeingRemoved = false"
                    >
                        Nevermind
                    </v-btn>

                    <v-btn
                        color="secondary"
                        @click.native="removeTeamMember" :class="{ 'opacity-25': removeTeamMemberForm.processing }" :disabled="removeTeamMemberForm.processing"
                    >
                        Remove
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
            'team',
            'availableRoles',
            'userPermissions'
        ],

        data() {
            return {
                addTeamMemberForm: this.$inertia.form({
                    email: '',
                    role: null,
                }),

                updateRoleForm: this.$inertia.form({
                    role: null,
                }),

                leaveTeamForm: this.$inertia.form(),
                removeTeamMemberForm: this.$inertia.form(),

                currentlyManagingRole: false,
                managingRoleFor: null,
                confirmingLeavingTeam: false,
                teamMemberBeingRemoved: null,
            }
        },

        methods: {
            addTeamMember() {
                this.addTeamMemberForm.post(route('team-members.store', this.team), {
                    errorBag: 'addTeamMember',
                    preserveScroll: true,
                    onSuccess: () => this.addTeamMemberForm.reset(),
                });
            },

            cancelTeamInvitation(invitation) {
                this.$inertia.delete(route('team-invitations.destroy', invitation), {
                    preserveScroll: true
                });
            },

            manageRole(teamMember) {
                this.managingRoleFor = teamMember
                this.updateRoleForm.role = teamMember.membership.role
                this.currentlyManagingRole = true
            },

            updateRole() {
                this.updateRoleForm.put(route('team-members.update', [this.team, this.managingRoleFor]), {
                    preserveScroll: true,
                    onSuccess: () => (this.currentlyManagingRole = false),
                })
            },

            confirmLeavingTeam() {
                this.confirmingLeavingTeam = true
            },

            leaveTeam() {
                this.leaveTeamForm.delete(route('team-members.destroy', [this.team, this.$page.props.user]))
            },

            confirmTeamMemberRemoval(teamMember) {
                this.teamMemberBeingRemoved = teamMember
            },

            removeTeamMember() {
                this.removeTeamMemberForm.delete(route('team-members.destroy', [this.team, this.teamMemberBeingRemoved]), {
                    errorBag: 'removeTeamMember',
                    preserveScroll: true,
                    preserveState: true,
                    onSuccess: () => (this.teamMemberBeingRemoved = null),
                })
            },

            displayableRole(role) {
                return this.availableRoles.find(r => r.key === role).name
            },
        },
    }
</script>

<style scoped>
    .v-btn-toggle {
        display: block;
    }
    .rounded-b-none {
        border-bottom-right-radius: 0px;
        border-bottom-left-radius: 0px;
    }
    .rounded-t-none{
        border-top-left-radius: 0px;
        border-top-right-radius: 0px
    }
    .team-btn {
        position: relative;
        width: 100%;
        padding-left: 1rem;
        padding-right: 1rem;
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
        display: inline-flex;
        border-radius: 0.5rem;
    }
    .team-btn:focus {
        z-index: 10;
        outline: 2px solid transparent;
        outline-offset: 2px;
        border: 1px solid rgb(147, 197, 253);
    }
    .text-sm {
        font-size: 0.875rem;
        line-height: 1.25rem;
    }
    .text-xs {
        font-size: 0.75rem;
        line-height: 1rem;
    }
    .font-semibold {
        font-weight: 600;
    }
    .text-gray-600 {
        color: rgb(75, 85, 99);
    }
</style>