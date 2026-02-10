<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { redirect as githubRedirect } from '@/routes/github';
</script>

<template>
    <GuestLayout>
        <v-card max-width="400" class="mx-auto pa-6" elevation="8" rounded="lg">
            <v-card-title class="text-h4 text-center mb-4">
                Laragen
            </v-card-title>
            <v-card-subtitle class="text-center mb-6">
                One-click OpenAPI generation for Laravel
            </v-card-subtitle>
            <v-card-text>
                <v-btn
                    color="grey-darken-4"
                    size="large"
                    block
                    :href="githubRedirect.url()"
                    prepend-icon="mdi-github"
                >
                    Sign in with GitHub
                </v-btn>
            </v-card-text>
        </v-card>
    </GuestLayout>
</template>
