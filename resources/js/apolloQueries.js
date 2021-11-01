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
