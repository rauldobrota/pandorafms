#docker build -t pandorafms/pandorafms-percona-base:80 .
#docker tag pandorafms/pandorafms-percona-base:80 pandorafms/pandorafms-percona-base:latest 
#docker push pandorafms/pandorafms-percona-base:80


FROM percona:8.0
ADD pandora.cnf /etc/my.cnf.d/
USER root
RUN chown mysql:mysql /etc/my.cnf.d/pandora.cnf
USER mysql
EXPOSE 3306
