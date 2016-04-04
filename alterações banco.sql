alter table turma add column observacao varchar(100);
alter table turma change observacao periodo varchar(15);
alter table estudante add column turma_atual integer unsigned not null;
ALTER TABLE estudante ADD CONSTRAINT `fk_turma` FOREIGN KEY (turma_atual) REFERENCES turma(id);
