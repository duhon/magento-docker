FROM openjdk:alpine

ENV JMETER_VERSION 3.1
ENV VNC_SERVER_PASSWORD secret
ENV JMETER_HOME /home/alpine/jmeter/apache-jmeter-$JMETER_VERSION/
ENV PATH $JMETER_HOME/bin:$PATH
ENV DISPLAY :99
ENV RESOLUTION 1024x768x24

RUN apk add --no-cache wget tar openssl sudo xvfb x11vnc xfce4 faenza-icon-theme bash unzip \
    && adduser -h /home/alpine -s /bin/bash -S -D alpine && echo -e "alpine\nalpine" | passwd alpine \
    && echo 'alpine ALL=(ALL) NOPASSWD:ALL' >> /etc/sudoers

USER alpine
WORKDIR /home/alpine

RUN mkdir -p /home/alpine/.vnc && x11vnc -storepasswd $VNC_SERVER_PASSWORD /home/alpine/.vnc/passwd

# Installing jmeter
RUN mkdir /home/alpine/jmeter \
    && cd /home/alpine/jmeter/ \
    && wget https://archive.apache.org/dist/jmeter/binaries/apache-jmeter-$JMETER_VERSION.tgz \
    && tar -xzf apache-jmeter-$JMETER_VERSION.tgz \
    && rm apache-jmeter-$JMETER_VERSION.tgz

RUN wget -qO- -O $JMETER_HOME/lib/ext/jmeter-plugins-manager.jar https://jmeter-plugins.org/get/
RUN wget -qO- -O tmp.zip http://jmeter-plugins.org/downloads/file/JMeterPlugins-Standard-1.2.1.zip && unzip -n tmp.zip -d $JMETER_HOME && rm tmp.zip
RUN wget -qO- -O tmp.zip http://jmeter-plugins.org/downloads/file/JMeterPlugins-Extras-1.3.0.zip && unzip -n tmp.zip -d $JMETER_HOME && rm tmp.zip
RUN wget -qO- -O tmp.zip http://jmeter-plugins.org/downloads/file/JMeterPlugins-ExtrasLibs-1.3.0.zip && unzip -n tmp.zip -d $JMETER_HOME && rm tmp.zip
RUN wget -qO- -O tmp.zip http://jmeter-plugins.org/files/packages/blazemeter-debugger-0.4.zip && unzip -n tmp.zip -d $JMETER_HOME && rm tmp.zip
RUN wget -qO- -O tmp.zip http://jmeter-plugins.org/files/packages/jpgc-json-2.6.zip && unzip -n tmp.zip -d $JMETER_HOME && rm tmp.zip
RUN chmod -R 777 $JMETER_HOME

COPY xfce4-session.xml /etc/xdg/xfce4/xfconf/xfce-perchannel-xml/xfce4-session.xml

WORKDIR $JMETER_HOME
COPY bootstrap.sh /bootstrap.sh
EXPOSE 5900

CMD [ "/bin/bash", "/bootstrap.sh" ]
