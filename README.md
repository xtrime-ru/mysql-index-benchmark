# Mysql Index Benchmark
Benchmark to compare multi-column indexes with different selectivity order

## What are we benchmarking?

We compare two same tables with same data, but with different order of collumns in multicolumn index.

TableA has increasing selectivity of index and TableB - decreasing.

## Run

```shell 
docker compose run --rm benchmark
```

## Example of output

```text
[2023-10-03 15:30:17] Create tables ... 
[2023-10-03 15:30:18] Filling table A ... 
[2023-10-03 15:30:22] Copy rows from table A to B ... 
[2023-10-03 15:30:26] Start benchmark: (gender = 'male' AND user_id = 1024) 
[2023-10-03 15:30:26] Table employes_a avg requests time: 0.133 ms 
[2023-10-03 15:30:26] employes_a count by query: 0
[2023-10-03 15:30:26] employes_a explain: {
  "query_block": {
    "select_id": 1,
    "r_loops": 1,
    "r_total_time_ms": 0.00587738,
    "nested_loop": [
      {
        "table": {
          "table_name": "employes_a",
          "access_type": "ref",
          "possible_keys": ["gender_user_id"],
          "key": "gender_user_id",
          "key_length": "7",
          "used_key_parts": ["gender", "user_id"],
          "ref": ["const", "const"],
          "r_loops": 1,
          "rows": 1,
          "r_rows": 0,
          "r_table_time_ms": 0.001830635,
          "r_other_time_ms": 0.002586702,
          "filtered": 100,
          "r_filtered": 100,
          "attached_condition": "employes_a.gender = 'male'",
          "using_index": true
        }
      }
    ]
  }
}
[2023-10-03 15:30:26] Finish benchmark
[2023-10-03 15:30:26] -----------------------------
[2023-10-03 15:30:26] Start benchmark: (gender = 'male' AND user_id = 1024)
[2023-10-03 15:30:26] Table employes_b avg requests time: 0.113 ms
[2023-10-03 15:30:26] employes_b count by query: 0
[2023-10-03 15:30:26] employes_b explain: {
  "query_block": {
    "select_id": 1,
    "r_loops": 1,
    "r_total_time_ms": 0.006274762,
    "nested_loop": [
      {
        "table": {
          "table_name": "employes_b",
          "access_type": "ref",
          "possible_keys": ["user_id_gender"],
          "key": "user_id_gender",
          "key_length": "7",
          "used_key_parts": ["user_id", "gender"],
          "ref": ["const", "const"],
          "r_loops": 1,
          "rows": 1,
          "r_rows": 0,
          "r_table_time_ms": 0.00247359,
          "r_other_time_ms": 0.002171461,
          "filtered": 100,
          "r_filtered": 100,
          "attached_condition": "employes_b.gender = 'male'",
          "using_index": true
        }
      }
    ]
  }
}
[2023-10-03 15:30:26] Finish benchmark
[2023-10-03 15:30:26] -----------------------------
[2023-10-03 15:30:26] Start benchmark: (gender <> 'female' AND user_id <> 1024)
[2023-10-03 15:30:30] Table employes_a avg requests time: 173.193 ms 
[2023-10-03 15:30:30] employes_a count by query: 499803 
[2023-10-03 15:30:31] employes_a explain: {
  "query_block": {
    "select_id": 1,
    "r_loops": 1,
    "r_total_time_ms": 144.9225483,
    "nested_loop": [
      {
        "table": {
          "table_name": "employes_a",
          "access_type": "index",
          "possible_keys": ["gender_user_id"],
          "key": "gender_user_id",
          "key_length": "7",
          "used_key_parts": ["gender", "user_id"],
          "r_loops": 1,
          "rows": 1000000,
          "r_rows": 1000000,
          "r_table_time_ms": 100.5546516,
          "r_other_time_ms": 44.36393853,
          "filtered": 100,
          "r_filtered": 49.9803,
          "attached_condition": "employes_a.gender <> 'female' and employes_a.user_id <> 1024",
          "using_index": true
        }
      }
    ]
  }
}
[2023-10-03 15:30:31] Finish benchmark
[2023-10-03 15:30:31] -----------------------------
[2023-10-03 15:30:31] Start benchmark: (gender <> 'female' AND user_id <> 1024)
[2023-10-03 15:30:35] Table employes_b avg requests time: 174.849 ms 
[2023-10-03 15:30:35] employes_b count by query: 499803 
[2023-10-03 15:30:35] employes_b explain: {
  "query_block": {
    "select_id": 1,
    "r_loops": 1,
    "r_total_time_ms": 150.116575,
    "nested_loop": [
      {
        "table": {
          "table_name": "employes_b",
          "access_type": "range",
          "possible_keys": ["user_id_gender"],
          "key": "user_id_gender",
          "key_length": "5",
          "used_key_parts": ["user_id"],
          "r_loops": 1,
          "rows": 501028,
          "r_rows": 1000000,
          "r_table_time_ms": 92.79279905,
          "r_other_time_ms": 57.32011919,
          "filtered": 100,
          "r_filtered": 49.9803,
          "attached_condition": "employes_b.gender <> 'female' and employes_b.user_id <> 1024",
          "using_index": true
        }
      }
    ]
  }
}
[2023-10-03 15:30:35] Finish benchmark
[2023-10-03 15:30:35] -----------------------------
[2023-10-03 15:30:35] Start benchmark: (gender NOT IN ('male') AND user_id IN (1024, 52048, 812345, 123456))
[2023-10-03 15:30:38] Table employes_a avg requests time: 124.829 ms 
[2023-10-03 15:30:39] employes_a count by query: 1 
[2023-10-03 15:30:39] employes_a explain: {
  "query_block": {
    "select_id": 1,
    "r_loops": 1,
    "r_total_time_ms": 143.9305938,
    "nested_loop": [
      {
        "table": {
          "table_name": "employes_a",
          "access_type": "index",
          "possible_keys": ["gender_user_id"],
          "key": "gender_user_id",
          "key_length": "7",
          "used_key_parts": ["gender", "user_id"],
          "r_loops": 1,
          "rows": 1000000,
          "r_rows": 1000000,
          "r_table_time_ms": 95.81678553,
          "r_other_time_ms": 48.10865716,
          "filtered": 100,
          "r_filtered": 0.0001,
          "attached_condition": "employes_a.gender <> 'male' and employes_a.user_id in (1024,52048,812345,123456)",
          "using_index": true
        }
      }
    ]
  }
}
[2023-10-03 15:30:39] Finish benchmark
[2023-10-03 15:30:39] -----------------------------
[2023-10-03 15:30:39] Start benchmark: (gender NOT IN ('male') AND user_id IN (1024, 52048, 812345, 123456))
[2023-10-03 15:30:39] Table employes_b avg requests time: 0.131 ms 
[2023-10-03 15:30:39] employes_b count by query: 1
[2023-10-03 15:30:39] employes_b explain: {
  "query_block": {
    "select_id": 1,
    "r_loops": 1,
    "r_total_time_ms": 0.024747059,
    "nested_loop": [
      {
        "table": {
          "table_name": "employes_b",
          "access_type": "range",
          "possible_keys": ["user_id_gender"],
          "key": "user_id_gender",
          "key_length": "5",
          "used_key_parts": ["user_id"],
          "r_loops": 1,
          "rows": 4,
          "r_rows": 3,
          "r_table_time_ms": 0.011975776,
          "r_other_time_ms": 0.00851915,
          "filtered": 100,
          "r_filtered": 33.33333333,
          "attached_condition": "employes_b.gender <> 'male' and employes_b.user_id in (1024,52048,812345,123456)",
          "using_index": true
        }
      }
    ]
  }
}
[2023-10-03 15:30:39] Finish benchmark
[2023-10-03 15:30:39] -----------------------------
[2023-10-03 15:30:39] Start benchmark: (gender IN ('female') AND user_id NOT IN (1024, 52048, 812345, 123456))
[2023-10-03 15:30:42] Table employes_a avg requests time: 114.81 ms 
[2023-10-03 15:30:42] employes_a count by query: 500196 
[2023-10-03 15:30:42] employes_a explain: {
  "query_block": {
    "select_id": 1,
    "r_loops": 1,
    "r_total_time_ms": 88.56849944,
    "nested_loop": [
      {
        "table": {
          "table_name": "employes_a",
          "access_type": "range",
          "possible_keys": ["gender_user_id"],
          "key": "gender_user_id",
          "key_length": "7",
          "used_key_parts": ["gender", "user_id"],
          "r_loops": 1,
          "rows": 826488,
          "r_rows": 500196,
          "r_table_time_ms": 49.07091258,
          "r_other_time_ms": 39.49345528,
          "filtered": 82.64880371,
          "r_filtered": 100,
          "attached_condition": "employes_a.gender = 'female' and employes_a.user_id not in (1024,52048,812345,123456)",
          "using_index": true
        }
      }
    ]
  }
}
[2023-10-03 15:30:42] Finish benchmark
[2023-10-03 15:30:42] -----------------------------
[2023-10-03 15:30:42] Start benchmark: (gender IN ('female') AND user_id NOT IN (1024, 52048, 812345, 123456))
[2023-10-03 15:30:46] Table employes_b avg requests time: 170.025 ms 
[2023-10-03 15:30:46] employes_b count by query: 500196 
[2023-10-03 15:30:46] employes_b explain: {
  "query_block": {
    "select_id": 1,
    "r_loops": 1,
    "r_total_time_ms": 156.0124438,
    "nested_loop": [
      {
        "table": {
          "table_name": "employes_b",
          "access_type": "index",
          "possible_keys": ["user_id_gender"],
          "key": "user_id_gender",
          "key_length": "7",
          "used_key_parts": ["user_id", "gender"],
          "r_loops": 1,
          "rows": 1000000,
          "r_rows": 1000000,
          "r_table_time_ms": 93.90146099,
          "r_other_time_ms": 62.1082793,
          "filtered": 100,
          "r_filtered": 50.0196,
          "attached_condition": "employes_b.gender = 'female' and employes_b.user_id not in (1024,52048,812345,123456)",
          "using_index": true
        }
      }
    ]
  }
}
[2023-10-03 15:30:46] Finish benchmark
[2023-10-03 15:30:46] -----------------------------
[2023-10-03 15:30:46] Start benchmark: (user_id IN (-1))
[2023-10-03 15:30:49] Table employes_a avg requests time: 99.882 ms 
[2023-10-03 15:30:49] employes_a count by query: 0 
[2023-10-03 15:30:49] employes_a explain: {
  "query_block": {
    "select_id": 1,
    "r_loops": 1,
    "r_total_time_ms": 118.5830652,
    "nested_loop": [
      {
        "table": {
          "table_name": "employes_a",
          "access_type": "index",
          "key": "gender_user_id",
          "key_length": "7",
          "used_key_parts": ["gender", "user_id"],
          "r_loops": 1,
          "rows": 1000000,
          "r_rows": 1000000,
          "r_table_time_ms": 93.49256001,
          "r_other_time_ms": 25.08484287,
          "filtered": 100,
          "r_filtered": 0,
          "attached_condition": "employes_a.user_id = -1",
          "using_index": true
        }
      }
    ]
  }
}
[2023-10-03 15:30:49] Finish benchmark
[2023-10-03 15:30:49] -----------------------------
[2023-10-03 15:30:49] Start benchmark: (user_id IN (-1))
[2023-10-03 15:30:49] Table employes_b avg requests time: 0.174 ms 
[2023-10-03 15:30:49] employes_b count by query: 0
[2023-10-03 15:30:49] employes_b explain: {
  "query_block": {
    "select_id": 1,
    "table": {
      "message": "no matching row in const table"
    }
  }
}
[2023-10-03 15:30:49] Finish benchmark
[2023-10-03 15:30:49] -----------------------------
[2023-10-03 15:30:49] Start benchmark: (gender IN ('UNKNOWN'))
[2023-10-03 15:30:49] Table employes_a avg requests time: 0.114 ms
[2023-10-03 15:30:49] employes_a count by query: 0
[2023-10-03 15:30:49] employes_a explain: {
  "query_block": {
    "select_id": 1,
    "table": {
      "message": "no matching row in const table"
    }
  }
}
[2023-10-03 15:30:49] Finish benchmark
[2023-10-03 15:30:49] -----------------------------
[2023-10-03 15:30:49] Start benchmark: (gender IN ('UNKNOWN'))
[2023-10-03 15:30:52] Table employes_b avg requests time: 97.87 ms 
[2023-10-03 15:30:52] employes_b count by query: 0 
[2023-10-03 15:30:52] employes_b explain: {
  "query_block": {
    "select_id": 1,
    "r_loops": 1,
    "r_total_time_ms": 116.8153077,
    "nested_loop": [
      {
        "table": {
          "table_name": "employes_b",
          "access_type": "index",
          "key": "user_id_gender",
          "key_length": "7",
          "used_key_parts": ["user_id", "gender"],
          "r_loops": 1,
          "rows": 1000000,
          "r_rows": 1000000,
          "r_table_time_ms": 86.87354539,
          "r_other_time_ms": 29.93690368,
          "filtered": 100,
          "r_filtered": 0,
          "attached_condition": "employes_b.gender = 'UNKNOWN'",
          "using_index": true
        }
      }
    ]
  }
}
[2023-10-03 15:30:52] Finish benchmark
[2023-10-03 15:30:52] -----------------------------
[2023-10-03 15:30:52] Start benchmark: (user_id IN (1024, 2048, 12345, 7890123456))
[2023-10-03 15:30:55] Table employes_a avg requests time: 112.428 ms 
[2023-10-03 15:30:55] employes_a count by query: 0 
[2023-10-03 15:30:55] employes_a explain: {
  "query_block": {
    "select_id": 1,
    "r_loops": 1,
    "r_total_time_ms": 130.1880393,
    "nested_loop": [
      {
        "table": {
          "table_name": "employes_a",
          "access_type": "index",
          "key": "gender_user_id",
          "key_length": "7",
          "used_key_parts": ["gender", "user_id"],
          "r_loops": 1,
          "rows": 1000000,
          "r_rows": 1000000,
          "r_table_time_ms": 89.87655213,
          "r_other_time_ms": 40.30595062,
          "filtered": 100,
          "r_filtered": 0,
          "attached_condition": "employes_a.user_id in (1024,2048,12345,7890123456)",
          "using_index": true
        }
      }
    ]
  }
}
[2023-10-03 15:30:55] Finish benchmark
[2023-10-03 15:30:55] -----------------------------
[2023-10-03 15:30:55] Start benchmark: (user_id IN (1024, 2048, 12345, 7890123456))
[2023-10-03 15:30:55] Table employes_b avg requests time: 0.185 ms 
[2023-10-03 15:30:55] employes_b count by query: 0
[2023-10-03 15:30:55] employes_b explain: {
  "query_block": {
    "select_id": 1,
    "r_loops": 1,
    "r_total_time_ms": 0.006567217,
    "nested_loop": [
      {
        "table": {
          "table_name": "employes_b",
          "access_type": "range",
          "possible_keys": ["user_id_gender"],
          "key": "user_id_gender",
          "key_length": "5",
          "used_key_parts": ["user_id"],
          "r_loops": 1,
          "rows": 3,
          "r_rows": 0,
          "r_table_time_ms": 0.002777951,
          "r_other_time_ms": 0.002219831,
          "filtered": 100,
          "r_filtered": 100,
          "attached_condition": "employes_b.user_id in (1024,2048,12345,7890123456)",
          "using_index": true
        }
      }
    ]
  }
}
[2023-10-03 15:30:55] Finish benchmark
[2023-10-03 15:30:55] -----------------------------
[2023-10-03 15:30:55] Start benchmark: (gender IN ('female'))
[2023-10-03 15:30:57] Table employes_a avg requests time: 92.132 ms 
[2023-10-03 15:30:57] employes_a count by query: 500197 
[2023-10-03 15:30:57] employes_a explain: {
  "query_block": {
    "select_id": 1,
    "r_loops": 1,
    "r_total_time_ms": 69.5642353,
    "nested_loop": [
      {
        "table": {
          "table_name": "employes_a",
          "access_type": "ref",
          "possible_keys": ["gender_user_id"],
          "key": "gender_user_id",
          "key_length": "2",
          "used_key_parts": ["gender"],
          "ref": ["const"],
          "r_loops": 1,
          "rows": 500000,
          "r_rows": 500197,
          "r_table_time_ms": 51.04353749,
          "r_other_time_ms": 18.51706929,
          "filtered": 100,
          "r_filtered": 100,
          "attached_condition": "employes_a.gender = 'female'",
          "using_index": true
        }
      }
    ]
  }
}
[2023-10-03 15:30:57] Finish benchmark
[2023-10-03 15:30:57] -----------------------------
[2023-10-03 15:30:57] Start benchmark: (gender IN ('female'))
[2023-10-03 15:31:01] Table employes_b avg requests time: 147.662 ms 
[2023-10-03 15:31:01] employes_b count by query: 500197 
[2023-10-03 15:31:01] employes_b explain: {
  "query_block": {
    "select_id": 1,
    "r_loops": 1,
    "r_total_time_ms": 132.8606053,
    "nested_loop": [
      {
        "table": {
          "table_name": "employes_b",
          "access_type": "index",
          "key": "user_id_gender",
          "key_length": "7",
          "used_key_parts": ["user_id", "gender"],
          "r_loops": 1,
          "rows": 1000000,
          "r_rows": 1000000,
          "r_table_time_ms": 89.72555116,
          "r_other_time_ms": 43.13087936,
          "filtered": 100,
          "r_filtered": 50.0197,
          "attached_condition": "employes_b.gender = 'female'",
          "using_index": true
        }
      }
    ]
  }
}
[2023-10-03 15:31:01] Finish benchmark
[2023-10-03 15:31:01] -----------------------------
[2023-10-03 15:31:01] Start benchmark: (gender IS NOT NULL)
[2023-10-03 15:31:06] Table employes_a avg requests time: 173.645 ms 
[2023-10-03 15:31:06] employes_a count by query: 1000000 
[2023-10-03 15:31:06] employes_a explain: {
  "query_block": {
    "select_id": 1,
    "r_loops": 1,
    "r_total_time_ms": 131.5292362,
    "nested_loop": [
      {
        "table": {
          "table_name": "employes_a",
          "access_type": "range",
          "possible_keys": ["gender_user_id"],
          "key": "gender_user_id",
          "key_length": "2",
          "used_key_parts": ["gender"],
          "r_loops": 1,
          "rows": 500000,
          "r_rows": 1000000,
          "r_table_time_ms": 96.64513968,
          "r_other_time_ms": 34.87924679,
          "filtered": 100,
          "r_filtered": 100,
          "attached_condition": "employes_a.gender is not null",
          "using_index": true
        }
      }
    ]
  }
}
[2023-10-03 15:31:06] Finish benchmark
[2023-10-03 15:31:06] -----------------------------
[2023-10-03 15:31:06] Start benchmark: (gender IS NOT NULL)
[2023-10-03 15:31:10] Table employes_b avg requests time: 160.002 ms 
[2023-10-03 15:31:10] employes_b count by query: 1000000 
[2023-10-03 15:31:10] employes_b explain: {
  "query_block": {
    "select_id": 1,
    "r_loops": 1,
    "r_total_time_ms": 120.0528779,
    "nested_loop": [
      {
        "table": {
          "table_name": "employes_b",
          "access_type": "index",
          "key": "user_id_gender",
          "key_length": "7",
          "used_key_parts": ["user_id", "gender"],
          "r_loops": 1,
          "rows": 1000000,
          "r_rows": 1000000,
          "r_table_time_ms": 93.54565327,
          "r_other_time_ms": 26.50155863,
          "filtered": 100,
          "r_filtered": 100,
          "attached_condition": "employes_b.gender is not null",
          "using_index": true
        }
      }
    ]
  }
}
[2023-10-03 15:31:10] Finish benchmark
[2023-10-03 15:31:10] -----------------------------
[2023-10-03 15:31:10] Start benchmark: (user_id IS NOT NULL)
[2023-10-03 15:31:14] Table employes_a avg requests time: 163.419 ms 
[2023-10-03 15:31:14] employes_a count by query: 1000000 
[2023-10-03 15:31:15] employes_a explain: {
  "query_block": {
    "select_id": 1,
    "r_loops": 1,
    "r_total_time_ms": 121.7557253,
    "nested_loop": [
      {
        "table": {
          "table_name": "employes_a",
          "access_type": "index",
          "key": "gender_user_id",
          "key_length": "7",
          "used_key_parts": ["gender", "user_id"],
          "r_loops": 1,
          "rows": 1000000,
          "r_rows": 1000000,
          "r_table_time_ms": 95.25727948,
          "r_other_time_ms": 26.49437451,
          "filtered": 100,
          "r_filtered": 100,
          "attached_condition": "employes_a.user_id is not null",
          "using_index": true
        }
      }
    ]
  }
}
[2023-10-03 15:31:15] Finish benchmark
[2023-10-03 15:31:15] -----------------------------
[2023-10-03 15:31:15] Start benchmark: (user_id IS NOT NULL)
[2023-10-03 15:31:19] Table employes_b avg requests time: 169.103 ms 
[2023-10-03 15:31:19] employes_b count by query: 1000000 
[2023-10-03 15:31:19] employes_b explain: {
  "query_block": {
    "select_id": 1,
    "r_loops": 1,
    "r_total_time_ms": 129.4169078,
    "nested_loop": [
      {
        "table": {
          "table_name": "employes_b",
          "access_type": "range",
          "possible_keys": ["user_id_gender"],
          "key": "user_id_gender",
          "key_length": "5",
          "used_key_parts": ["user_id"],
          "r_loops": 1,
          "rows": 500000,
          "r_rows": 1000000,
          "r_table_time_ms": 94.39795859,
          "r_other_time_ms": 35.01427286,
          "filtered": 100,
          "r_filtered": 100,
          "attached_condition": "employes_b.user_id is not null",
          "using_index": true
        }
      }
    ]
  }
}
[2023-10-03 15:31:19] Finish benchmark
[2023-10-03 15:31:19] -----------------------------
```

## Results

What do we see?