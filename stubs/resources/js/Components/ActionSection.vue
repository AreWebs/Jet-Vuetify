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
            <v-card class="elevation-2 px-4 pt-5">
                <v-card-text>
                    <slot name="content"/>
                </v-card-text>
            </v-card>
        </v-col>
    </v-row>
</template>

<style scoped>
v-card-text {
    padding: 1.5rem;
    padding-bottom: 0;
}
</style>