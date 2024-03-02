<?php

namespace App\Http\Controllers\MercadoLivre;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GeneratechartsSneakers extends Controller
{

    public function getMainAttribute(){
        return json_decode('
        {"attributes": [
            {
                "site_id": "MLB",
                "id": "BR_SIZE"
            }
        ]}
        ',true);
    }

    public function getAttributesSneakers(){
        return json_decode('{"rows": [
            {
                "attributes": [
                    {
                        "id": "BR_SIZE",
                        "values": [
                            {
                                "name": "33 BR"
                            }
                        ]
                    },
                    {
                        "id": "FOOT_LENGTH",
                        "values": [
                            {
                                "name": "22.5 cm"
                            }
                        ]
                    }
                ]
            },
            {
                "attributes": [
                    {
                        "id": "BR_SIZE",
                        "values": [
                            {
                                "name": "34 BR"
                            }
                        ]
                    },
                    {
                        "id": "FOOT_LENGTH",
                        "values": [
                            {
                                "name": "23.0 cm"
                            }
                        ]
                    }
                ]
            },
               {
                "attributes": [
                    {
                        "id": "BR_SIZE",
                        "values": [
                            {
                                "name": "35 BR"
                            }
                        ]
                    },
                    {
                        "id": "FOOT_LENGTH",
                        "values": [
                            {
                                "name": "24.0 cm"
                            }
                        ]
                    }
                ]
            },   {
                "attributes": [
                    {
                        "id": "BR_SIZE",
                        "values": [
                            {
                                "name": "36 BR"
                            }
                        ]
                    },
                    {
                        "id": "FOOT_LENGTH",
                        "values": [
                            {
                                "name": "24.7 cm"
                            }
                        ]
                    }
                ]
            },   {
                "attributes": [
                    {
                        "id": "BR_SIZE",
                        "values": [
                            {
                                "name": "37 BR"
                            }
                        ]
                    },
                    {
                        "id": "FOOT_LENGTH",
                        "values": [
                            {
                                "name": "25.3 cm"
                            }
                        ]
                    }
                ]
            },   {
                "attributes": [
                    {
                        "id": "BR_SIZE",
                        "values": [
                            {
                                "name": "38 BR"
                            }
                        ]
                    },
                    {
                        "id": "FOOT_LENGTH",
                        "values": [
                            {
                                "name": "26.0 cm"
                            }
                        ]
                    }
                ]
            },   {
                "attributes": [
                    {
                        "id": "BR_SIZE",
                        "values": [
                            {
                                "name": "39 BR"
                            }
                        ]
                    },
                    {
                        "id": "FOOT_LENGTH",
                        "values": [
                            {
                                "name": "26.7 cm"
                            }
                        ]
                    }
                ]
            },   {
                "attributes": [
                    {
                        "id": "BR_SIZE",
                        "values": [
                            {
                                "name": "40 BR"
                            }
                        ]
                    },
                    {
                        "id": "FOOT_LENGTH",
                        "values": [
                            {
                                "name": "27.0 cm"
                            }
                        ]
                    }
                ]
            },   {
                "attributes": [
                    {
                        "id": "BR_SIZE",
                        "values": [
                            {
                                "name": "41 BR"
                            }
                        ]
                    },
                    {
                        "id": "FOOT_LENGTH",
                        "values": [
                            {
                                "name": "27.5 cm"
                            }
                        ]
                    }
                ]
            },   {
                "attributes": [
                    {
                        "id": "BR_SIZE",
                        "values": [
                            {
                                "name": "42 BR"
                            }
                        ]
                    },
                    {
                        "id": "FOOT_LENGTH",
                        "values": [
                            {
                                "name": "28.0 cm"
                            }
                        ]
                    }
                ]
            },   {
                "attributes": [
                    {
                        "id": "BR_SIZE",
                        "values": [
                            {
                                "name": "43 BR"
                            }
                        ]
                    },
                    {
                        "id": "FOOT_LENGTH",
                        "values": [
                            {
                                "name": "29.0 cm"
                            }
                        ]
                    }
                ]
            },   {
                "attributes": [
                    {
                        "id": "BR_SIZE",
                        "values": [
                            {
                                "name": "44 BR"
                            }
                        ]
                    },
                    {
                        "id": "FOOT_LENGTH",
                        "values": [
                            {
                                "name": "30.0 cm"
                            }
                        ]
                    }
                ]
            },   {
                "attributes": [
                    {
                        "id": "BR_SIZE",
                        "values": [
                            {
                                "name": "45 BR"
                            }
                        ]
                    },
                    {
                        "id": "FOOT_LENGTH",
                        "values": [
                            {
                                "name": "31.0 cm"
                            }
                        ]
                    }
                ]
            },   {
                "attributes": [
                    {
                        "id": "BR_SIZE",
                        "values": [
                            {
                                "name": "46 BR"
                            }
                        ]
                    },
                    {
                        "id": "FOOT_LENGTH",
                        "values": [
                            {
                                "name": "32.0 cm"
                            }
                        ]
                    }
                ]
            }
        ]}',true);
    }
}
