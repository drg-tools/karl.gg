export default {
    character: (id) => `query {
                  character(id: ${id}){
                    name
                    id
                    guns {
                      id
                      name
                      gun_class
                      character_slot
                      json_stats
                      image
                      overclocks {
                        id
                        overclock_index
                        overclock_name
                        overclock_type
                        icon
                        text_description
                        json_stats
                        credits_cost
                        magnite_cost
                        bismor_cost
                        umanite_cost
                        croppa_cost
                        enor_pearl_cost
                        jadiz_cost
                      }
                      mods {
                        id
                        mod_tier
                        mod_index
                        mod_name
                        mod_type
                        icon
                        text_description
                        json_stats
                        credits_cost
                        magnite_cost
                        bismor_cost
                        umanite_cost
                        croppa_cost
                        enor_pearl_cost
                        jadiz_cost
                      }
                    }
                    equipments {
                      id
                      name
                      eq_type
                      description
                      icon
                      json_stats
                      equipment_mods {
                        id
                        mod_tier
                        mod_index
                        mod_name
                        mod_type
                        icon
                        description
                        json_stats
                        credits_cost
                        magnite_cost
                        bismor_cost
                        umanite_cost
                        croppa_cost
                        enor_pearl_cost
                        jadiz_cost
                      }
                    }
                    throwables {
                      id
                      name
                      description
                      icon
                      json_stats
                      credits_cost
                      magnite_cost
                      bismor_cost
                      umanite_cost
                      croppa_cost
                      enor_pearl_cost
                      jadiz_cost
                    }
                  }
                }`,
    popularLoadouts: `query {
                      loadouts {
                          id
                          name
                          description
                          created_at
                          updated_at
                          votes
                          creator {
                            id
                            name
                          }
                          character {
                            id
                            name
                          }
                          mods {
                            id
                            gun {
                              id
                              name
                              character_slot
                            }
                          }
                        }
                      }`,

    loadoutDetails: (id) => `query {
                      loadout(id: ${id}) {
                        id
                        name
                        description
                        created_at
                        updated_at
                        votes
                        creator {
                          id
                          name
                        }
                        character {
                          id
                          name
                        }
                        mods {
                          id
                          mod_name
                          text_description
                          mod_tier
                          mod_index
                          mod_type
                          gun {
                            id
                            name
                            character_slot
                            character {
                                id
                            }
                          }
                        }
                        overclocks {
                          id
                          overclock_name
                          text_description
                          overclock_index
                          overclock_type
                          gun {
                            id
                            name
                            character_slot
                            character {
                                id
                            }
                          }
                        }
                        equipment_mods {
                          id
                          mod_name
                          description
                          mod_tier
                          mod_index
                          equipment {
                            id
                            icon
                            name
                            character {
                                id
                            }
                          }
                        }
                        throwable {
                          id
                          name
                          description
                          icon
                          json_stats
                          credits_cost
                          magnite_cost
                          bismor_cost
                          umanite_cost
                          croppa_cost
                          enor_pearl_cost
                          jadiz_cost
                        }
                      }
                    }`,
    getModsForGun: (id) => `query {
                          gun(id: ${id}) {
                            id
                            mods {
                              id
                              mod_tier
                              mod_index
                            }
                          }
                        }`,
    getModsForEquipment: (id) => `query {
                          equipment(id: ${id}) {
                            id
                            equipment_mods {
                              id
                              mod_tier
                              mod_index
                            }
                          }
                        }`,
    getBuildMetricsByCombo: (combo, gunId) => `{
  buildMetricByCombo(build_combination: "${combo}", gun_id: ${gunId}) {
    id
    build_combination
    ideal_burst_dps
    ideal_burst_dps
    burst_dps_wp
    burst_dps_acc
    burst_dps_aw
    burst_dps_wp_acc
    burst_dps_wp_aw
    burst_dps_acc_aw
    burst_dps_wp_acc_aw
    ideal_sustained_dps
    sustained_dps_wp
    sustained_dps_acc
    sustained_dps_aw
    sustained_dps_wp_acc
    sustained_dps_wp_aw
    sustained_dps_acc_aw
    sustained_dps_wp_acc_aw
    ideal_additional_target_dps
    max_num_targets_per_shot
    max_multi_target_damage
    ammo_efficiency
    damage_wasted_by_armor
    general_accuracy
    weakpoint_accuracy
    firing_duration
    average_time_to_kill
    average_overkill
    breakpoints
    utility
    average_time_to_ignite_or_freeze
    damage_per_magazine
    time_to_fire_magazine
  }
}`,
    myLoadouts: `query myLoadouts($userId: [Int!]) {
                          myLoadouts(userId: $userId) {
                              id
                              name
                              description
                              created_at
                              updated_at
                              votes
                              creator {
                                id
                                name
                              }
                              character {
                                id
                                name
                              }
                              mods {
                                id
                                gun {
                                  id
                                  name
                                  character_slot
                                }
                              }
                            }
                          }`,
};
