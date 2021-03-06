USE [Alumnado]
GO
/****** Object:  StoredProcedure [dbo].[Materias_Alumno]    Script Date: 11/06/2017 08:43:17 p.m. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
ALTER PROCEDURE [dbo].[Materias_Alumno] 

	-- Parametros
	@id_carrera int,
	@id_alumno int,
	@anio_lectivo int

AS
BEGIN

	SET NOCOUNT ON;

	select 
	      materia.NOMBRE,
		  [AÑO] as anio,
		  duracionmat.descripcion, 
	      (select case when  Exists(Select materiasregulares.idmateria  from materiasregulares where materiasregulares.idalumno=@id_alumno and materiasregulares.idmateria=materia.id) then 
		        'Regular' else 'Libre' end) as estado,
		  materia.id
		from  materia INNER JOIN duracionmat ON materia.idduracionmat = duracionmat.id    
		where materia.idcarrera = @id_carrera
		-- 1) Filtro las materias que ya esta Inscripto
		And  Not Exists (Select inscripcioncursado.idmateria  from inscripcioncursado where inscripcioncursado.idalumno =  @id_alumno and inscripcioncursado.idmateria = materia.id  and inscripcioncursado.año_lectivo = @anio_lectivo)
		-- 2) Filtro las materias que ya aprobo
		And  Not Exists (Select materiaaprobada.idmateria  from materiaaprobada where materiaaprobada.idalumno =  @id_alumno and materiaaprobada.idmateria = materia.id )
		
		order by AÑO;
END
