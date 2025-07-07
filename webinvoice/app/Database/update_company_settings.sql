UPDATE settings 
SET setting_value = CASE setting_key
    WHEN 'name' THEN 'PT. Indo Succes Abadi'
    WHEN 'address' THEN 'Jl. Mh Thamrin No.1, Bekasi'
    WHEN 'email' THEN 'IndoSucces@gmail.com'
    WHEN 'phone' THEN '+021 899089'
END
WHERE setting_group = 'company' 
AND setting_key IN ('name', 'address', 'email', 'phone'); 