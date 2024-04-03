# smartlead

wp-json/custom-api/v1/categorias

# Migration banco API

~~~sql
CREATE VIEW products_view AS
SELECT 
    id,
    name,
    cod,
    description,
    specifications,
    recordings,
    included,
    features,
    internal_part,
    external_part,
    observations,
    budget_id,
    deleted_at,
    created_at,
    updated_at,
    LOWER(REGEXP_REPLACE(CONCAT(name, '_', cod), '[^a-zA-Z0-9]+', '-', 'g')) AS slug
FROM 
    products;
~~~
