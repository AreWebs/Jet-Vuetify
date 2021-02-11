<template>
    <v-row justify="center">
        <v-col cols="11" md="3">
            <h3 class="">
                <slot name="title"></slot>
            </h3>

            <p class="mt-1 text--secondary">
                <slot name="description"></slot>
            </p>
        </v-col>
        <v-col cols="11" md="9">
            <v-card class="elevation-2 pt-5">
                <form @submit.prevent="$emit('submitted')">
                    <v-card-text>
                        <slot name="form"/>
                    </v-card-text>

                    <v-card-actions v-if="hasActions" class="justify-end pr-6 py-2">
                        <slot name="actions"></slot>
                    </v-card-actions>
                </form>
            </v-card>
        </v-col>
    </v-row>
</template>

<script>
export default {
    computed: {
        hasActions() {
            return !! this.$slots.actions
        }
    }
}
</script>

<style scoped>
.v-card__text {
    padding: 1.5rem;
    padding-bottom: 0;
}
.v-card__actions {
    background-color: #f3f4f6;
    /* position: relative;
    width: 102%;
    left: -17px; */
}
</style>