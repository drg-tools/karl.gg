export default {
    characterById: (id) => `query {
                      character(id: ${id}) {
                        name
                        id
                        guns {
                          id
                          name
                          gun_class
                          character_slot
                          json_stats
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
    character: `query character($id: ID!) {
                  character(id: $id){
                    name
                    guns {
                      id
                      name
                      gun_class
                      character_slot
                      json_stats
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
                          mod_tier
                          mod_index
                          mod_type
                          gun {
                            id
                            name
                            character_slot
                          }
                        }
                        overclocks {
                          id
                          overclock_name
                          overclock_index
                          overclock_type
                          gun {
                            id
                            name
                            character_slot
                          }
                        }
                        equipment_mods {
                          id
                          mod_name
                          mod_tier
                          mod_index
                          equipment {
                            id
                            icon
                            name
                          }
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
                        }`
};
