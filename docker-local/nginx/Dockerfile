FROM amazonlinux:2

RUN yum -y update

RUN ln -sf /usr/share/zoneinfo/Asia/Tokyo /etc/localtime

RUN amazon-linux-extras install nginx1.12 -y

RUN yum clean all

EXPOSE 80
ENTRYPOINT ["/usr/sbin/nginx", "-g", "daemon off;"]
