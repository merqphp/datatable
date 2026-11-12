<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3'
import type { UsersPageProps } from '../../types'

defineProps<UsersPageProps>()
</script>

<template>
    <main>
        <Head title="Users — Vue" />
        <h1>DataTable Playground</h1>
        <nav aria-label="Frontend">
            <a href="/react/users">React</a>
            ·
            <a href="/vue/users" aria-current="page">Vue</a>
        </nav>

        <h2>Users</h2>
        <button type="button" @click="router.reload()">Reload users</button>

        <table>
            <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="user in users" :key="user.id">
                    <td>{{ user.name }}</td>
                    <td>{{ user.email }}</td>
                </tr>
            </tbody>
        </table>

        <p v-if="users.length === 0">No users yet.</p>
    </main>
</template>
