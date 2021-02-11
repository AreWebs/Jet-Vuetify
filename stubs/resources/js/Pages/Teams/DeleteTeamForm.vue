<template>
    <app-action-section>
        <template #title>
            Delete Team
        </template>

        <template #description>
            Permanently delete this team.
        </template>

        <template #content>
            <div class="text-sm text-gray-600">
                Once a team is deleted, all of its resources and data will be permanently deleted. Before deleting this team, please download any data or information regarding this team that you wish to retain.
            </div>

            <div class="mt-5">
                <v-btn color="red" @click.native="confirmTeamDeletion">
                    Delete Team
                </v-btn>
            </div>

            <!-- Delete Team Confirmation Modal -->
            <v-dialog
                v-model="confirmingTeamDeletion"
                max-width="672"
                >
                <v-card>
                    <v-card-title class="headline">
                        Delete Team
                    </v-card-title>

                    <v-card-text>
                        Are you sure you want to delete this team? Once a team is deleted, all of its resources and data will be permanently deleted.
                    </v-card-text>

                    <v-card-actions class="px-6 py-4">
                        <v-spacer></v-spacer>
                        <v-btn
                            outlined
                            @click.native="confirmingTeamDeletion = false"
                        >
                            Nevermind
                        </v-btn>

                        <v-btn
                            color="secondary" dark
                            @click.native="deleteTeam" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                        >
                            Leave
                        </v-btn>
                    </v-card-actions>

                </v-card>
            </v-dialog>                
        </template>
    </app-action-section>
</template>

<script>
    import AppActionSection from '@/Components/ActionSection'

    export default {
        props: ['team'],

        components: {
            AppActionSection,
        },

        data() {
            return {
                confirmingTeamDeletion: false,
                deleting: false,

                form: this.$inertia.form()
            }
        },

        methods: {
            confirmTeamDeletion() {
                this.confirmingTeamDeletion = true
            },

            deleteTeam() {
                this.form.delete(route('teams.destroy', this.team), {
                    errorBag: 'deleteTeam'
                });
            },
        },
    }
</script>
