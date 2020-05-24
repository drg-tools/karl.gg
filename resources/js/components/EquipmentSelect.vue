<template>
	<div class="weaponSelectContainer" v-on:click="selectEquip()" :title="[getModified ? 'Equipment is modified' : '']">
		<div class="flexboxWeaponSelect" :class="[getSelected ? 'equipmentActive' : 'equipment']">
			<svg xmlns="http://www.w3.org/2000/svg"
			     viewBox="0 0 180 90"
			     :class="[getSelected ? 'equipmentIconActive' : 'equipmentIcon']"
			     height="70%"
			     preserveAspectRatio="xMidYMid meet"
			     v-html="getIconFromPath"></svg>
		</div>
		<div :class="[getSelected ? 'equipmentTextActive' : 'equipmentText']">
			<h4>{{ name }}<span v-if="getModified"> *</span></h4>
		</div>
	</div>
</template>

<script>
	import store from "../store";

	export default {
		name: "EquipmentSelect",
		props: {
			iconPath: String,
			name: String,
			classId: String,
			equipmentId: String,
			data: Object
		},
		computed: {
			getIconFromPath: function() {
				let aPath = this.iconPath.split(".");
				if (aPath.length < 2) {
					return "";
				}
				return store.state.icons[aPath[0]][aPath[1]];
			},
			getSelected: function() {
				return this.data.selected;
			},
			getModified: function() {
				return store.state.tree[this.classId][this.equipmentId].modified;
			}
		},
		methods: {
			selectEquip() {
				store.commit("selectEquipment", {
					classID: this.classId,
					equipID: this.equipmentId
				});
				store.commit("deSelectOtherEquipments", {
					classID: this.classId,
					equipID: this.equipmentId
				});
			}
		}
	};
</script>

<style scoped>
	h4 {
		white-space: nowrap;
	}

	.weaponSelectContainer {
		display: flex;
		cursor: pointer;
	}

	.weaponSelectContainer:hover .equipmentText {
		color: #fffbff;
	}

	.weaponSelectContainer:hover .equipmentIcon {
		fill: #fffbff;
	}

	.flexboxWeaponSelect {
		display: flex;
		align-items: center;
		height: 5rem;
	}

	.flexboxWeaponSelect > svg {
		margin: 0.5rem;
	}
</style>
