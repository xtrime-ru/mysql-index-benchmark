# Mysql Index Benchmark
Benchmark to compare multi-column indexes with different selectivity order

## What are we benchmarking?

We compare two same tables with same data, but with different order of collumns in multicolumn index.

TableA has increasing selectivity of index and TableB - decreasing.

## Run

```shell
docker compose run --rm becnhmark
```

## Example of output

```text
[2023-05-11 23:18:07] Create tables ... 
[2023-05-11 23:18:07] Filling table A ... 
[2023-05-11 23:18:07] Copy rows from table A to B ... 
[2023-05-11 23:18:10] Start benchmark ... 
[2023-05-11 23:18:10] employes_a explain: {
  "query_block": {
    "select_id": 1,
    "r_loops": 1,
    "r_total_time_ms": 11.346,
    "nested_loop": [
      {
        "table": {
          "table_name": "employes_a",
          "access_type": "range",
          "possible_keys": ["gender_age"],
          "key": "gender_age",
          "key_length": "4",
          "used_key_parts": ["gender", "age"],
          "r_loops": 1,
          "rows": 193764,
          "r_rows": 94873,
          "r_table_time_ms": 5.431791667,
          "r_other_time_ms": 5.909583333,
          "filtered": 19.40385628,
          "r_filtered": 100,
          "attached_condition": "employes_a.gender = 'male' and employes_a.age >= 20 and employes_a.age <= 30",
          "using_index": true
        }
      }
    ]
  }
} 
[2023-05-11 23:18:10] employes_b explain: {
  "query_block": {
    "select_id": 1,
    "r_loops": 1,
    "r_total_time_ms": 18.18983333,
    "nested_loop": [
      {
        "table": {
          "table_name": "employes_b",
          "access_type": "range",
          "possible_keys": ["age_gender"],
          "key": "age_gender",
          "key_length": "4",
          "used_key_parts": ["age", "gender"],
          "r_loops": 1,
          "rows": 350128,
          "r_rows": 181048,
          "r_table_time_ms": 10.249625,
          "r_other_time_ms": 7.937875,
          "filtered": 100,
          "r_filtered": 52.4021254,
          "attached_condition": "employes_b.gender = 'male' and employes_b.age >= 20 and employes_b.age <= 30",
          "using_index": true
        }
      }
    ]
  }
} 
[2023-05-11 23:18:16] Table employes_a avg requests time: 11.159 ms 
[2023-05-11 23:18:25] Table employes_b avg requests time: 17.752 ms 
[2023-05-11 23:18:25] employes_a count by query: 94873 
[2023-05-11 23:18:25] employes_b count by query: 94873 
```

## Results
When using b-tree indexes make sure to make multicolumn indexes with increasing selectivity for columns order.
In our example correct index is `INDEX gender_age (gender, age)`