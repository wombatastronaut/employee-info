<template>
    <div class="loader" v-if="loading" :style="{ 'position': position }">
        <sync-loader :loading="loading"></sync-loader>
    </div>
</template>

<script>
    import SyncLoader from 'vue-spinner/src/SyncLoader.vue'

	export default{
        components: {
            SyncLoader
        },
		props: {
			loading: {
				default: false
			},
			position: {
				default: 'fixed'
			}
		}
	}
</script>

<style lang="scss">
    .loader {
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, .75);
        z-index: 1000;
        left: 0;
        top: 0;

        .v-spinner {
            position: absolute;
            left: 50%;
            top: 300px;
            transform: translate(-50%, -50%);
            font-size: 5em;
            color: #06D796;
        }
    }
</style>
