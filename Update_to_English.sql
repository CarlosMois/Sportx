-- Translation of Sports Names
UPDATE deportes SET nombre = 'Soccer' WHERE nombre = 'Fútbol';
UPDATE deportes SET nombre = 'Volleyball' WHERE nombre = 'Voleibol';
UPDATE deportes SET nombre = 'Basketball' WHERE nombre = 'Baloncesto';
UPDATE deportes SET nombre = 'Olympic Wrestling' WHERE nombre = 'Lucha Olímpica';
UPDATE deportes SET nombre = 'Ballet' WHERE nombre = 'Ballet';

-- Translation of Unit Types (Costo Centro)
UPDATE costo_centro SET unidad = 'per_hour' WHERE unidad = 'por_hora';
UPDATE costo_centro SET unidad = 'per_class' WHERE unidad = 'por_clase';
UPDATE costo_centro SET unidad = 'monthly' WHERE unidad = 'mensual';
UPDATE costo_centro SET unidad = 'court_use' WHERE unidad = 'uso de cancha';

-- Translation of Days of the Week (Horarios)
UPDATE horarios SET dia_semana = 'monday' WHERE dia_semana = 'lunes';
UPDATE horarios SET dia_semana = 'tuesday' WHERE dia_semana = 'martes';
UPDATE horarios SET dia_semana = 'wednesday' WHERE dia_semana = 'miercoles';
UPDATE horarios SET dia_semana = 'thursday' WHERE dia_semana = 'jueves';
UPDATE horarios SET dia_semana = 'friday' WHERE dia_semana = 'viernes';
UPDATE horarios SET dia_semana = 'saturday' WHERE dia_semana = 'sabado';
UPDATE horarios SET dia_semana = 'sunday' WHERE dia_semana = 'domingo';

-- Note: Since you want all information translated,
-- you should also translate the 'descripcion' fields in 'centros_deportivos'
-- and 'deportes' tables manually or via a script if the data is already populated.
