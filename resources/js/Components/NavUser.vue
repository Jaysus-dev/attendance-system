<script setup lang="ts">
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

import {
    BadgeCheck,
    Bell,
    ChevronsUpDown,
    CreditCard,
    LogOut,
    Sparkles,
    User,
} from "lucide-vue-next";

import { Link } from "@inertiajs/vue3";

import { Avatar, AvatarFallback, AvatarImage } from "@/Components/ui/avatar";

import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from "@/Components/ui/dropdown-menu";

import {
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    useSidebar,
} from "@/Components/ui/sidebar";

/*
|--------------------------------------------------------------------------
| Get Logged In User From Inertia
|--------------------------------------------------------------------------
*/

const page = usePage();

const user = computed(() => ({
    name: page.props.auth.user.name,
    email: page.props.auth.user.email,
    role: page.props.auth.user.role,
    avatar: "/avatars/shadcn.jpg",
}));

const { isMobile } = useSidebar();
</script>

<template>
    <SidebarMenu>
        <SidebarMenuItem>
            <DropdownMenu>
                <!-- TRIGGER -->
                <DropdownMenuTrigger as-child>
                    <SidebarMenuButton
                        size="lg"
                        class="data-[state=open]:bg-sidebar-accent data-[state=open]:text-sidebar-accent-foreground"
                    >
                        <!-- AVATAR -->
                        <Avatar class="h-8 w-8 rounded-lg">
                            <AvatarImage :src="user.avatar" :alt="user.name" />

                            <AvatarFallback class="rounded-lg">
                                {{ user.name.charAt(0) }}
                            </AvatarFallback>
                        </Avatar>

                        <!-- USER INFO -->
                        <div
                            class="grid flex-1 text-left text-sm leading-tight"
                        >
                            <span class="truncate font-medium">
                                {{ user.name }}
                            </span>

                            <span
                                class="truncate text-xs text-muted-foreground capitalize"
                            >
                                <!--{{ user.email }}-->
                                {{ user.role }}
                            </span>
                        </div>

                        <ChevronsUpDown class="ml-auto size-4" />
                    </SidebarMenuButton>
                </DropdownMenuTrigger>

                <!-- DROPDOWN -->
                <DropdownMenuContent
                    class="w-(--reka-dropdown-menu-trigger-width) min-w-56 rounded-lg"
                    :side="isMobile ? 'bottom' : 'right'"
                    align="end"
                    :side-offset="4"
                >
                    <!-- USER HEADER -->
                    <DropdownMenuLabel class="p-0 font-normal">
                        <div
                            class="flex items-center gap-2 px-2 py-2 text-left text-sm"
                        >
                            <Avatar class="h-8 w-8 rounded-lg">
                                <AvatarImage
                                    :src="user.avatar"
                                    :alt="user.name"
                                />

                                <AvatarFallback class="rounded-lg">
                                    {{ user.name.charAt(0) }}
                                </AvatarFallback>
                            </Avatar>

                            <div
                                class="grid flex-1 text-left text-sm leading-tight"
                            >
                                <span class="truncate font-semibold">
                                    {{ user.name }}
                                </span>

                                <span
                                    class="truncate text-xs text-muted-foreground"
                                >
                                    {{ user.email }}
                                </span>
                            </div>
                        </div>
                    </DropdownMenuLabel>

                    <DropdownMenuSeparator />

                    <!-- OPTIONAL -->
                    <DropdownMenuGroup>
                        <DropdownMenuItem>
                            <Sparkles class="mr-2 h-4 w-4" />
                            Upgrade to Pro
                        </DropdownMenuItem>
                    </DropdownMenuGroup>

                    <DropdownMenuSeparator />

                    <!-- ACCOUNT -->
                    <DropdownMenuGroup>
                        <DropdownMenuItem as-child>
                            <Link :href="route('profile.edit')">
                                <User class="mr-2 h-4 w-4" />
                                Profile
                            </Link>
                        </DropdownMenuItem>

                        <DropdownMenuItem>
                            <CreditCard class="mr-2 h-4 w-4" />
                            Billing
                        </DropdownMenuItem>

                        <DropdownMenuItem>
                            <Bell class="mr-2 h-4 w-4" />
                            Notifications
                        </DropdownMenuItem>
                    </DropdownMenuGroup>

                    <DropdownMenuSeparator />

                    <!-- LOGOUT -->
                    <DropdownMenuItem as-child>
                        <Link
                            :href="route('logout')"
                            method="post"
                            as="button"
                            class="w-full"
                        >
                            <LogOut class="mr-2 h-4 w-4" />
                            Log out
                        </Link>
                    </DropdownMenuItem>
                </DropdownMenuContent>
            </DropdownMenu>
        </SidebarMenuItem>
    </SidebarMenu>
</template>
