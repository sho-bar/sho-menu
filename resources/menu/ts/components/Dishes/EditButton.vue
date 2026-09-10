<script setup lang="ts">
import PencilIcon from '@/components/Icons/PencilIcon.vue'

type Props = {
    id: number
}

const { id } = defineProps<Props>()
</script>

<template>
    <a
        v-if="shoMenuGlobals.isAuth === '1'"
        @click.stop
        :href="`/wp-admin/post.php?post=${id}&action=edit`"
        class="sho-menu__dishes__item__edit"
    >
        <pencil-icon width="20" height="20" />
    </a>
</template>
