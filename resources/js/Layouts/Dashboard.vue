<script lang="ts">
export const iframeHeight = '800px'
export const description = 'A sidebar with collapsible sections.'
import 'vue-sonner/style.css'
</script>

<script setup lang="ts">
import AppSidebar from '@/components/AppSidebar.vue'
import {
    Breadcrumb,
    BreadcrumbItem,
    BreadcrumbLink,
    BreadcrumbList,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from '@/components/ui/breadcrumb'
import Button from '@/components/ui/button/Button.vue'
import { Separator } from '@/components/ui/separator'
import {
    SidebarInset,
    SidebarProvider,
    SidebarTrigger,
} from '@/components/ui/sidebar'
import { LogOut } from 'lucide-vue-next'
import { Toaster } from 'vue-sonner'

defineProps({
    title: {
        type: String,
        required: false,
    }
});
</script>

<template>
    <Toaster richColors closeButton position="top-center" :duration="5000" />
    <SidebarProvider>
        <AppSidebar />
        <SidebarInset>
            <header class="flex sticky top-0 bg-background h-16 shrink-0 items-center gap-2 border-b px-4">
                <SidebarTrigger class="-ml-1" />
                <Separator orientation="vertical" class="mr-2 h-4" />
                <Breadcrumb>
                    <BreadcrumbList>
                        <BreadcrumbItem class="hidden md:block">
                            <BreadcrumbLink href="#">
                                Dashboard
                            </BreadcrumbLink>
                        </BreadcrumbItem>
                        <BreadcrumbSeparator v-if="title" class="hidden md:block" />
                        <BreadcrumbItem v-if="title">
                            <BreadcrumbPage>{{ title }}</BreadcrumbPage>
                        </BreadcrumbItem>
                    </BreadcrumbList>
                </Breadcrumb>
            </header>
            <div class="flex flex-1 flex-col gap-4 p-4">
                <slot />
            </div>
        </SidebarInset>
    </SidebarProvider>
</template>
