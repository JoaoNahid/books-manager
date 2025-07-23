<script setup>
import {
  Sidebar,
  SidebarContent,
  SidebarGroup,
  SidebarGroupContent,
  SidebarGroupLabel,
  SidebarMenu,
  SidebarMenuButton,
  SidebarMenuItem,
  SidebarRail,
} from '@/components/ui/sidebar';
import SidebarFooter from './ui/sidebar/SidebarFooter.vue';
import Button from './ui/button/Button.vue';
import { LogOut } from 'lucide-vue-next';
import { toast } from 'vue-sonner';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  side: { type: String, required: false },
  variant: { type: String, required: false },
  collapsible: { type: String, required: false },
  class: { type: null, required: false },
});

const logout = () => {
  router.post('/logout', {
    onSuccess: () => toast.success('Deslogado do sistema!')
  })
}

// This is sample data.
const data = {
  versions: ["1.0.1", "1.1.0-alpha", "2.0.0-beta1"],
  navMain: [
    {
      title: "Gerenciador de Livros",
      url: "#",
      items: [
        {
          title: "Dashboard",
          url: "/dashboard",
        },
        {
          title: "Livros",
          url: "/books",
        },
      ],
    },
  ],
};
</script>

<template>
  <Sidebar v-bind="props">
    <SidebarContent>
      <SidebarGroup v-for="item in data.navMain" :key="item.title">
        <SidebarGroupLabel>{{ item.title }}</SidebarGroupLabel>
        <SidebarGroupContent>
          <SidebarMenu>
            <SidebarMenuItem
              v-for="childItem in item.items"
              :key="childItem.title"
            >
              <SidebarMenuButton as-child :is-active="childItem.isActive">
                <a :href="childItem.url">{{ childItem.title }}</a>
              </SidebarMenuButton>
            </SidebarMenuItem>
          </SidebarMenu>
        </SidebarGroupContent>
      </SidebarGroup>
    </SidebarContent>
    <SidebarFooter>
      <Button @click="logout" variant="ghost" class="cursor-pointer justify-start rounded">
          <LogOut />
          Sair
      </Button>
    </SidebarFooter>
    <SidebarRail />
  </Sidebar>
</template>
