<script setup lang="ts">
import type { LucideIcon } from "lucide-vue-next";
import { ChevronRight } from "lucide-vue-next";

import {
    Collapsible,
    CollapsibleContent,
    CollapsibleTrigger,
} from "@/Components/ui/collapsible";

import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarMenuSub,
    SidebarMenuSubButton,
    SidebarMenuSubItem,
} from "@/Components/ui/sidebar";
import { Link } from "@inertiajs/vue3";

defineProps<{
    items: {
        title: string;
        url: string;
        icon?: LucideIcon;
        isActive?: boolean;
        collapsible?: boolean;
        items?: {
            title: string;
            url: string;
        }[];
    }[];
}>();
</script>

<template>
    <SidebarGroup>
        <SidebarGroupLabel>MAIN</SidebarGroupLabel>

        <SidebarMenu>
            <template v-for="item in items" :key="item.title">
                <!-- COLLAPSIBLE ITEM -->
                <Collapsible
                    v-if="item.collapsible"
                    as-child
                    :default-open="item.isActive"
                    class="group/collapsible"
                >
                    <SidebarMenuItem>
                        <CollapsibleTrigger as-child>
                            <SidebarMenuButton :tooltip="item.title">
                                <component :is="item.icon" v-if="item.icon" />

                                <span>{{ item.title }}</span>

                                <ChevronRight
                                    class="ml-auto transition-transform duration-200 group-data-[state=open]/collapsible:rotate-90"
                                />
                            </SidebarMenuButton>
                        </CollapsibleTrigger>

                        <CollapsibleContent>
                            <SidebarMenuSub>
                                <SidebarMenuSubItem
                                    v-for="subItem in item.items"
                                    :key="subItem.title"
                                >
                                    <SidebarMenuSubButton as-child>
                                        <Link :href="subItem.url">
                                            <span>{{ subItem.title }}</span>
                                        </Link>
                                    </SidebarMenuSubButton>
                                </SidebarMenuSubItem>
                            </SidebarMenuSub>
                        </CollapsibleContent>
                    </SidebarMenuItem>
                </Collapsible>

                <!-- NORMAL ITEM -->
                <SidebarMenuItem v-else>
                    <SidebarMenuButton as-child :is-active="item.isActive">
                        <Link :href="item.url">
                            <component :is="item.icon" v-if="item.icon" />

                            <span>{{ item.title }}</span>
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </template>
        </SidebarMenu>
    </SidebarGroup>
</template>
