export default {
    characterById: (id) => `query {
                      character(id: ${id}) {
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
                }`

};
