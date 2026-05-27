<script setup lang="ts">
import type { LucideIcon } from "lucide-vue-next";
import { Folder, Forward, MoreHorizontal, Trash2 } from "lucide-vue-next";

import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from "@/Components/ui/dropdown-menu";
import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuAction,
    SidebarMenuButton,
    SidebarMenuItem,
    useSidebar,
} from "@/Components/ui/sidebar";

defineProps<{
    projects: {
        name: string;
        url: string;
        icon: LucideIcon;
    }[];
}>();

const { isMobile } = useSidebar();
</script>
<template>
    <!-- ONLY SHOW IF THERE ARE PROJECTS -->
    <SidebarGroup
        v-if="projects.length > 0"
        class="group-data-[collapsible=icon]:hidden"
    >
        <SidebarGroupLabel>MANAGEMENT</SidebarGroupLabel>

        <SidebarMenu>
            <SidebarMenuItem v-for="item in projects" :key="item.name">
                <SidebarMenuButton as-child>
                    <a :href="item.url">
                        <component :is="item.icon" />
                        <span>{{ item.name }}</span>
                    </a>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>
