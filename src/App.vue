<script>
	import Vue from 'vue'; 

	export default {
		data() {
			return {
				difficulties: {
					0: {
						name: "Easy",
					},
					1: {
						name: "Medium",
					},
					2: {
						name: "Hard",
					},
				},
				page: 0,
				mode: 1, // 0: Local BlueVsRed | 1: Player vs Remote AI
				finish: false,
				turn: 1,
				textFinalSwitch: false,
				mouseHoverFirst: true
			};
		},
    mounted() {
      Vue.prototype.$fetch = this.fetch;
    },
		methods: {
			fetch(url, data, actions, actionf) {
				let dataEncoded = new URLSearchParams();
				for (let key in data) {
					dataEncoded.append(key, data[key]);
				}

				let requestData = {
					method: "POST",
					body: dataEncoded,
					redirect: "follow",
					mode: "cors",
				};

				fetch(url, requestData)
					.then((response) => response.json())
					.then((result) => actions(result))
					.catch((error) => actionf(error));
			}
		}
	};
</script>

<template>
  <div id="app">
    <router-view/>
  </div>
</template>

<style>

body {
  margin: 0;
  width: 100vw;
  height: 100vh;
}

#app {
  position: absolute;
  margin: 0;
  top: 0;
  left: 0;
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  width: 100%;
  height: 100%;
  background-image: url(./assets/bg.jpg);
  background-size: cover;
  background-repeat: no-repeat;
}

span {
  user-select: none;
}
</style>
