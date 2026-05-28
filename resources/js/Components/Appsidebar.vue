<script setup lang="ts">
import type { SidebarProps } from "@/Components/ui/sidebar";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

import {
    FileUser,
    SquareUser,
    BookSearch,
    Book,
    PieChart,
    UserRoundCheck,
    MessageSquareWarning,
    Users,
    Settings,
    CalendarCheck,
} from "lucide-vue-next";

import NavMain from "@/Components/NavMain.vue";
import NavProjects from "@/Components/NavProjects.vue";
import TeamSwitcher from "@/Components/TeamSwitcher.vue";

import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarRail,
} from "@/Components/ui/sidebar";

/*
|--------------------------------------------------------------------------
| Props
|--------------------------------------------------------------------------
*/

const props = withDefaults(defineProps<SidebarProps>(), {
    collapsible: "icon",
});

/*
|--------------------------------------------------------------------------
| Auth User
|--------------------------------------------------------------------------
*/

const page = usePage();

const user = computed(() => page.props.auth.user);

/*
|--------------------------------------------------------------------------
| Teams
|--------------------------------------------------------------------------
*/

const teams = [
    {
        name: "ATTENDANCE",
        logo: CalendarCheck,
        plan: "Management System",
    },
];

/*
|--------------------------------------------------------------------------
| Main Navigation
|--------------------------------------------------------------------------
*/

const navMain = computed(() => {
    // 👑 ADMIN SIDEBAR
    if (user.value.role === "admin") {
        return [
            {
                title: "Dashboard",
                url: route("dashboard"),
                icon: PieChart,
                isActive: route().current("dashboard"),
            },
            {
                title: "Students",
                url: route("students"),
                icon: FileUser,
                isActive: route().current("students"),
            },
            {
                title: "Teachers",
                url: route("teachers"),
                icon: SquareUser,
                isActive: route().current("teachers"),
            },
            {
                title: "Courses",
                url: route("courses"),
                icon: BookSearch,
                isActive: route().current("courses"),
            },
            {
                title: "Subjects",
                url: route("subjects"),
                icon: Book,
                isActive: route().current("subjects"),
            },
            {
                title: "Sections",
                url: route("sections"),
                icon: Book,
                isActive: route().current("sections"),
            },
        ];
    }
    if (user.value.role === "student") {
        return [
            {
                title: "My Attendance",
                url: route("student.subjects"),
                icon: CalendarCheck,
            },
        ];
    }

    // 👨‍🏫 TEACHER SIDEBAR
    return [
        {
            title: "Attendance",
            url: route("attendance"),
            icon: CalendarCheck,
            isActive: route().current("attendance"),
        },
    ];
});

/*
|--------------------------------------------------------------------------
| Projects Navigation
|--------------------------------------------------------------------------
*/

const projects = computed(() => {
    // 👑 ADMIN ONLY
    if (user.value.role === "admin") {
        return [
            {
                name: "Assignments",
                url: route("assignments"),
                icon: UserRoundCheck,
                isActive: route().current("assignments"),
            },
            {
                name: "Attendance",
                url: route("admin.teachers"),
                icon: CalendarCheck,
                isActive: route().current("admin.teachers"),
            },
            {
                name: "Users",
                url: route("admin.users"),
                icon: Users,
                isActive: route().current("admin.users"),
            },
            {
                name: "Reports",
                url: route("reports"),
                icon: MessageSquareWarning,
                isActive: route().current("reports"),
            },
            /** {
                name: "Settings",
                url: route("settings"),
                icon: Settings,
                isActive: route().current("settings"),
            }, */
        ];
    }

    // 👨‍🏫 TEACHER ONLY
    return [];
});
</script>

<template>
    <Sidebar v-bind="props">
        <SidebarHeader>
            <TeamSwitcher :teams="teams" />
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="navMain" />
            <NavProjects :projects="projects" />
        </SidebarContent>

        <SidebarFooter>
            <!-- Optional User Footer -->
        </SidebarFooter>

        <SidebarRail />
    </Sidebar>
</template>
