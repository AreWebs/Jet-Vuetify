<template>
    <app-form-section @submitted="updateTeamName">
        <template #title>
            Team Name
        </template>

        <template #description>
            The team's name and owner information.
        </template>

        <template #form>
            <!-- Team Owner Information -->
            <div class="col-span-6 mb-5">
                <div>Team Owner</div>

                <div class="d-flex items-center mt-2">
                    <v-avatar>
                        <img :src="team.owner.profile_photo_url" :alt="team.owner.name">
                    </v-avatar>

                    <div class="ml-4 leading-tight">
                        <div>{{ team.owner.name }}</div>
                        <div class="text-gray-700 text-sm">{{ team.owner.email }}</div>
                    </div>
                </div>
            </div>

            <!-- Team Name -->
            <v-text-field
                outlined label="Team Name" type="text" 
                v-model="form.name" :disabled="! permissions.canUpdateTeam"></v-text-field>

                <!-- <jet-input-error :message="form.errors.name" class="mt-2" /> -->
        </template>

        <template #actions v-if="permissions.canUpdateTeam">
            <transition 
                leave-active-class="transition ease-in duration-1000" 
                leave-class="opacity-100" 
                leave-to-class="opacity-0">
                <div v-show="form.recentlySuccessful" class="text-sm text-gray-600">
                    Saved.
                </div>
            </transition>

            <v-btn color="secondary ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" type="submit">
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

        props: ['team', 'permissions'],

        data() {
            return {
                form: this.$inertia.form({
                    name: this.team.name,
                })
            }
        },

        methods: {
            updateTeamName() {
                this.form.put(route('teams.update', this.team), {
                    errorBag: 'updateTeamName',
                    preserveScroll: true
                });
            },
        },
    }
</script>
