CREATE OR REPLACE VIEW v_objet_emprunte AS
SELECT * FROM Objet o 
JOIN emprunt e ON o.id_objet = e.id_objet;