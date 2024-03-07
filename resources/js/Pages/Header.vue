<script>
import {Link} from "@inertiajs/vue3";

export default {
    name: "Header",
    components: {Link},
}
</script>

<template>
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Logo</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="collapsibleNavbar">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <Link class="nav-link" :href="route('home.show-dashboard')">Dashboard</Link>
                    </li>
                    <li class="nav-item dropdown" v-if="$page.props.roles.includes('admin') || $page.props.roles.includes('company-admin')">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Manage Company</a>
                        <ul class="dropdown-menu">
                            <li><Link class="dropdown-item" :href="route('companies.show-profile')">Company Profile</Link></li>
                            <li><Link class="dropdown-item" :href="route('companies.show-users')">Company Users</Link></li>
                            <li><a class="dropdown-item" href="#">Company Payments</a></li>
                            <li><a class="dropdown-item" href="#">Company Wallet</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Manage Vehicles</a>
                    </li>
                    <li class="nav-item dropdown" v-if="$page.props.roles.includes('admin') || $page.props.roles.includes('customer-support')">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Customer Support</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Inquiries</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Account Settings</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Authentication</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown" v-if="$page.props.roles.includes('admin')">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Administration</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Companies</a></li>
                            <li><a class="dropdown-item" href="#">Vehicles</a></li>
                            <li><a class="dropdown-item" href="#">Users</a></li>
                            <li><a class="dropdown-item" href="#">Payments</a></li>
                            <li><a class="dropdown-item" href="#">App Settings</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown" v-if="$page.props.roles.includes('admin')">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">App Metadata</a>
                        <ul class="dropdown-menu">
                            <li><Link class="dropdown-item" :href="route('body-types.index')">Body Types</Link></li>
                            <li><Link class="dropdown-item" :href="route('drive-setups.index')">Drive Setups</Link></li>
                            <li><Link class="dropdown-item" :href="route('drive-types.index')">Drive Types</Link></li>
                            <li><Link class="dropdown-item" :href="route('makes.index')">Makes & Models</Link></li>
                            <li><Link class="dropdown-item" :href="route('transmission-types.index')">Transmission Types</Link></li>
                            <li><Link class="dropdown-item" :href="route('vehicle-conditions.index')">Vehicle Conditions</Link></li>
                            <li><Link class="dropdown-item" :href="route('counties.index')">Counties</Link></li>
                        </ul>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" :href="route('home.logout')">Logout ({{ $page.props.userName }})</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

</template>

<style scoped>

</style>
