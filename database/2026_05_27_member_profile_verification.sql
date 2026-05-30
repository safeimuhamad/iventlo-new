ALTER TABLE users
    ADD COLUMN IF NOT EXISTS birth_date DATE NULL AFTER email,
    ADD COLUMN IF NOT EXISTS gender ENUM('male', 'female') NULL AFTER birth_date;
