import { Head, router } from '@inertiajs/react'
import type { UsersPageProps } from '../../types'

export default function Users({ users }: UsersPageProps) {
    return (
        <main>
            <Head title="Users — React" />
            <h1>DataTable Playground</h1>
            <nav aria-label="Frontend">
                <a href="/react/users" aria-current="page">React</a>
                {' · '}
                <a href="/vue/users">Vue</a>
            </nav>

            <h2>Users</h2>
            <button type="button" onClick={() => router.reload()}>
                Reload users
            </button>

            <table>
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                    </tr>
                </thead>
                <tbody>
                    {users.map(user => (
                        <tr key={user.id}>
                            <td>{user.name}</td>
                            <td>{user.email}</td>
                        </tr>
                    ))}
                </tbody>
            </table>

            {users.length === 0 && <p>No users yet.</p>}
        </main>
    )
}
