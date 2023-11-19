<template>
    <div class="p-4 mx-auto w-4/5">
        <div>
            <Link :href="route('chats.index')">Chats</Link>
        </div>
        <div>
            <slot />
        </div>
    </div>
</template>

<script>
import { Link } from '@inertiajs/vue3';
export default {
    name: 'My',
    components: {
        Link,
    }
}
</script>

<style>

</style>